<?php

namespace Acme\TestBundle\Fixture\Document;

use Acme\TestBundle\Fixture\FixtureCollector;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Acme\Product\TshirtBundle\Document\TshirtProduct;

class LoadProductData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $tshirt = new TshirtProduct();
        $tshirt->setPrice(1);
        $manager->persist($tshirt);
        $manager->flush();

        FixtureCollector::addProduct(FixtureCollector::MONGODB, $tshirt->getId());
    }
}
