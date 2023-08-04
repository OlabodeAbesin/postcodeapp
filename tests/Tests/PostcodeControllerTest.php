<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostcodeControllerTest extends WebTestCase
{
    public function testPartialMatchAction()
    {
        $client = static::createClient();

        // Test partial match for "LONDON"
        $client->request('GET', '/api/postcodes/partial/LONDON');
        $this->assertResponseIsSuccessful();

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertCount(2, $responseData);

        // Assuming this app imported two postcodes with "LONDON" in their names
        $this->assertSame('LN1', $responseData[0]['postcode']);
        $this->assertSame('LN2', $responseData[1]['postcode']);
    }

    public function testNearbyAction()
    {
        $client = static::createClient();

        // Test nearby postcodes for latitude 51.5074 and longitude -0.1278
        $client->request('GET', '/api/postcodes/nearby/51.5074/-0.1278');
        $this->assertResponseIsSuccessful();

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertCount(2, $responseData);

        // Assuming this app imported two nearby postcodes
        $this->assertSame('NEARBY1', $responseData[0]['postcode']);
        $this->assertSame('NEARBY2', $responseData[1]['postcode']);
    }
}
