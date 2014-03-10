<?php

namespace Acme\Product\ConferenceBundle\Document;

use Acme\CartBundle\Document\Product as BaseProduct;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class TicketProduct extends BaseProduct
{
    /**
     * @MongoDB\Id(strategy="auto")
     */
    protected $id;

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
