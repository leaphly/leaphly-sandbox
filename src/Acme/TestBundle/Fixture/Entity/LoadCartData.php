<?php

namespace Acme\TestBundle\Fixture\Entity;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Acme\CartBundle\Entity\Cart;
use Acme\Product\ConferenceBundle\Entity\TicketItem;
use Acme\Product\TshirtBundle\Entity\TshirtItem;
use Acme\TestBundle\Fixture\FixtureCollector;

class LoadCartData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $cart = new Cart();
        $cart->setExpiresAt(new \DateTime('next day'));
        $cart->setIdentifier('1');
        $cart->setPrice(1);
        $cart->setFinalPrice(1);

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
