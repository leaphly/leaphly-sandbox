<?php

namespace Acme\SimplePurchaseProcessBundle\Tests\Fixture\Document;

use Acme\SimplePurchaseProcessBundle\Tests\Fixture\FixtureCollector;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Acme\CartBundle\Document\Cart;

class LoadCartData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $cart = new Cart();
        $cart->setPrice(0);
        $cart->setFinalPrice(0);

        $manager->persist($cart);
        $manager->flush();

        FixtureCollector::addCart(FixtureCollector::MONGODB, $cart);
    }
}
