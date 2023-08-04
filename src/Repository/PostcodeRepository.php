<?php

namespace App\Repository;

use App\Entity\Postcode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Postcode>
 *
 * @method Postcode|null find($id, $lockMode = null, $lockVersion = null)
 * @method Postcode|null findOneBy(array $criteria, array $orderBy = null)
 * @method Postcode[]    findAll()
 * @method Postcode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostcodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Postcode::class);
    }

    public function findByPartialMatch(string $searchString)
    {
        // Implement the logic to find postcodes with partial matches
        // You can use Doctrine QueryBuilder or DQL to perform the query
        // Example:
        return $this->createQueryBuilder('p')
            ->where('p.postcode LIKE :searchString')
            ->setParameter('searchString', '%' . $searchString . '%')
            ->getQuery()
            ->getResult();
    }

    public function findNearbyPostcodes(float $latitude, float $longitude)
    {
        // Implement the logic to find postcodes near the given latitude and longitude
        // You can use Doctrine QueryBuilder or DQL to perform the query
        // Example:
        return $this->createQueryBuilder('p')
            ->select('p, (6371 * ACOS(SIN(RADIANS(:lat)) * SIN(RADIANS(p.latitude)) + COS(RADIANS(:lat)) * COS(RADIANS(p.latitude)) * COS(RADIANS(:long - p.longitude)))) AS distance')
            ->setParameter('lat', $latitude)
            ->setParameter('long', $longitude)
            ->having('distance < 10') // Define your desired distance threshold in kilometers
            ->orderBy('distance', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
