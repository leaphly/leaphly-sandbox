<?php

namespace Acme\CartBundle\Tests\Fixture\Entity;

use Acme\CartBundle\Tests\Fixture\FixtureCollector;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Acme\CartBundle\Entity\Cart;
use Acme\Product\ConferenceBundle\Entity\TicketItem;
use Acme\Product\TshirtBundle\Entity\TshirtItem;

class LoadCartData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $cart = new Cart();
        $cart->setExpiresAt(new \DateTime('next day'));
        $cart->setIdentifier('1');
        $cart->setPrice(0);
        $cart->setFinalPrice(0);

        $ticket = new TicketItem();
        $ticket->setPrice(3);
        $ticket->setFinalPrice(3);
        $cart->addItem($ticket);

        $tShirt = new TshirtItem();
        $tShirt->setPrice(4);
        $tShirt->setFinalPrice(5);

        $cart->addItem($tShirt);

        $manager->persist($cart);
        $manager->flush();

        FixtureCollector::addCart(FixtureCollector::ORM, $cart);
    }
}
