<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Postcode;

class PostcodeController extends AbstractController
{
    /**
     * @Route("/api/postcodes/partial/{searchString}", name="api_postcodes_partial_match", methods={"GET"})
     */
    public function partialMatchAction(string $searchString): JsonResponse
    {
        $postcodes = $this->getDoctrine()->getRepository(Postcode::class)->findByPartialMatch($searchString);

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

    /**
     * @Route("/api/postcodes/nearby/{latitude}/{longitude}", name="api_postcodes_nearby", methods={"GET"})
     */
    public function nearbyAction(float $latitude, float $longitude): JsonResponse
    {
        $postcodes = $this->getDoctrine()->getRepository(Postcode::class)->findNearbyPostcodes($latitude, $longitude);

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
}
