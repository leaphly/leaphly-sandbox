<?php

namespace Acme\Product\ConferenceBundle\Entity;

use Acme\CartBundle\Entity\Product as BaseProduct;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class TicketProduct extends BaseProduct
{

    public function __construct()
    {
        $this->setName('ticket');
        // your own logic
    }

    public function getType()
    {
        return 'ticket';
    }
}
