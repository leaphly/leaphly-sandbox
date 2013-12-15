<?php

namespace Acme\CartBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Leaphly\Cart\Model\Cart as BaseCart;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Leaphly\Cart\Model\ItemInterface;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;
use Symfony\Component\Intl\ResourceBundle\CurrencyBundle;

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