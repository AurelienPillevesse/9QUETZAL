<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('div.postelement')->count());
    }

    public function testNewJokepostGet()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/jokepost/new');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
}
