<?php
namespace Acme\Product\ConferenceBundle\DataFixtures\MongoDB;

use Acme\Product\ConferenceBundle\Document\TicketProduct;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadTicketData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $ticket = new TicketProduct();
        $ticket->setName('Ticket 1');
        $ticket->setPrice(12.99);
        $manager->persist($ticket);

        $ticket = new TicketProduct();
        $ticket->setName('Ticket 2');
        $ticket->setPrice(10.09);
        $manager->persist($ticket);

        $manager->flush();
    }
}
