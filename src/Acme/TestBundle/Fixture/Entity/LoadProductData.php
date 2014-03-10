<?php

namespace Acme\TestBundle\Fixture\Entity;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;;
use Acme\Product\TshirtBundle\Entity\TshirtProduct;
use Acme\TestBundle\Fixture\FixtureCollector;

class LoadProductData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $tshirt = new TshirtProduct();
        $tshirt->setPrice(1);
        $manager->persist($tshirt);
        $manager->flush();

        FixtureCollector::addProduct(FixtureCollector::ORM, $tshirt->getId());
    }
}
