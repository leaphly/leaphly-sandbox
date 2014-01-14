<?php
namespace Acme\Product\TshirtBundle\DataFixtures\MongoDB;

use Acme\Product\TshirtBundle\Document\TshirtProduct;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadTshirtData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $tshirt = new TshirtProduct();
        $tshirt->setName('Red Tshirt');
        $tshirt->setPrice(20.99);
        $manager->persist($tshirt);

        $tshirt = new TshirtProduct();
        $tshirt->setName('Blue Tshirt');
        $tshirt->setPrice(22.99);
        $manager->persist($tshirt);

        $manager->flush();
    }
}
