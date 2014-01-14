<?php

namespace Acme\TestBundle\Fixture\Document;

use Acme\TestBundle\Fixture\FixtureCollector;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Acme\Product\ConferenceBundle\Document\TicketItem;
use Acme\Product\TshirtBundle\Document\TshirtItem;
use Acme\CartBundle\Document\Cart;

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
        //$ticket->setConferenceName('leaphly day');
        $cart->addItem($ticket);

        $tshirt = new TshirtItem();
        $tshirt->setPrice(4);
        $tshirt->setFinalPrice(5);
        $cart->addItem($tshirt);

        $manager->persist($cart);
        $manager->flush();

        FixtureCollector::addCart(FixtureCollector::MONGODB, $cart);
    }
}
