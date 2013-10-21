<?php

namespace Leaphly\ContentBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class PageControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $this->loadFixtures(
            array('Acme\Product\TshirtBundle\DataFixtures\MongoDB\LoadTshirtData',
                'Acme\Product\ConferenceBundle\DataFixtures\MongoDB\LoadTicketData'
            ),
            null,
            'doctrine_mongodb');
        $crawler = $client->request('GET', '/');

        $this->assertTrue($crawler->filter('html:contains("Leaphly")')->count() > 0);
    }
}
