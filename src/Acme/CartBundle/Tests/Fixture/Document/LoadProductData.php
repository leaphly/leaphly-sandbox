<?php

namespace Acme\CartBundle\Tests\Fixture\Document;

use Acme\CartBundle\Tests\Fixture\FixtureCollector;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Acme\Product\ConferenceBundle\Document\TicketProduct;
use Acme\Product\TshirtBundle\Document\TshirtProduct;

class LoadProductData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $tshirt = new TshirtProduct();
        $tshirt->setPrice(1);
        $manager->persist($tshirt);

        $ticket = new TicketProduct();
        $ticket->setPrice(1);
        $manager->persist($ticket);
        $manager->flush();

        FixtureCollector::addProduct(FixtureCollector::MONGODB, $tshirt->getId());
        FixtureCollector::addProduct(FixtureCollector::MONGODB, $ticket->getId());
    }
}
