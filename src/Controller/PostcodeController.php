<?php

namespace App\Controller;

use App\Entity\Postcode;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class PostcodeController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager, ManagerRegistry $registry)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/api/postcodes/partial/{searchString}', name: 'api_postcodes_partial_match', methods: ['GET'])]
    public function partialMatchAction(string $searchString): JsonResponse
    {
        $repository = $this->entityManager->getRepository(Postcode::class);
        $postcodes = $repository->createQueryBuilder('p')
            ->where('p.postcode LIKE :searchString')
            ->setParameter('searchString', '%'.$searchString.'%')
            ->getQuery()
            ->getResult();

        $response = [];
        foreach ($postcodes as $postcode) {
            $response[] = [
                'postcode' => $postcode->getPostcode(),
                'latitude' => $postcode->getLatitude(),
                'longitude' => $postcode->getLongitude(),
            ];
        }

        return new JsonResponse($response);
    }

    #[Route('/api/postcodes/nearby/{latitude}/{longitude}', name: 'api_postcodes_nearby', methods: ['GET'])]
    public function nearbyAction(float $latitude, float $longitude): JsonResponse
    {
        $repository = $this->entityManager->getRepository(Postcode::class);
        $dql = 'SELECT p, '.
            '('.$this->getDistanceFormula($latitude, $longitude).') AS distance '.
            'FROM '.Postcode::class.' p '.
            'HAVING distance < 10 '.
            'ORDER BY distance ASC';

        $query = $this->entityManager->createQuery($dql)
            ->setParameter('lat', $latitude)
            ->setParameter('long', $longitude);

        $postcodes = $query->getResult();

        $response = [];
        foreach ($postcodes as $postcode) {
            $response[] = [
                'postcode' => $postcode->getPostcode(),
                'latitude' => $postcode->getLatitude(),
                'longitude' => $postcode->getLongitude(),
            ];
        }

        return new JsonResponse($response);
    }

    private function getDistanceFormula(float $latitude, float $longitude): string
    {
        $latitudeExpression = 'RADIANS(:lat)';
        $longitudeExpression = 'RADIANS(:long)';

        $distanceExpression = '6371 * 2 * SIN(SQRT('.
            'POWER(SIN(('.$latitudeExpression.' - RADIANS(p.latitude)) / 2), 2) + '.
            'COS('.$latitudeExpression.') * COS(RADIANS(p.latitude)) * '.
            'POWER(SIN(('.$longitudeExpression.' - RADIANS(p.longitude)) / 2), 2)'.
            '))';

        return $distanceExpression;
    }
}
