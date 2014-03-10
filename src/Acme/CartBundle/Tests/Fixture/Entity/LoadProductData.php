<?php

namespace Acme\CartBundle\Tests\Fixture\Entity;

use Acme\CartBundle\Tests\Fixture\FixtureCollector;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Acme\Product\ConferenceBundle\Entity\TicketProduct;
use Acme\Product\TshirtBundle\Entity\TshirtProduct;

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

        FixtureCollector::addProduct(FixtureCollector::ORM, $tshirt->getId());
        FixtureCollector::addProduct(FixtureCollector::ORM, $ticket->getId());
    }
}
