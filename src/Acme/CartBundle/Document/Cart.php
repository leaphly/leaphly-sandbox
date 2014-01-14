<?php

namespace Acme\CartBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Leaphly\Cart\Model\Cart as BaseCart;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class Cart extends BaseCart
{
    /**
     * @MongoDB\Id(strategy="auto")
     */
    protected $id;

    /**
     * @MongoDB\EmbedMany
     */
    protected $items;

    /**
     * @MongoDB\String()
     */
    protected $customer;

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->currency = 'EUR';
    }

    /**
     * @param mixed $customer
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;
    }

    /**
     * @return mixed
     */
    public function getCustomer()
    {
        return $this->customer;
    }
}
