<?php

namespace Acme\Product\TshirtBundle\Document;

use Leaphly\Cart\Model\Item as BaseItem;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\EmbeddedDocument
 */
class TshirtItem extends BaseItem
{
    /**
     * @MongoDB\Id(strategy="auto")
     */
    protected $id;

    /**
     * @var
     * @MongoDB\Field(type="string")
     */
    protected $color;

    /**
     * @var
     * @MongoDB\Field(type="string")
     */
    protected $sku;

    /**
     * @var
     * @MongoDB\Field(type="int")
     */
    protected $quantity;

    /**
     * @var
     * @MongoDB\ReferenceOne(targetDocument="TshirtProduct")
     */
    protected $product;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
    }

    /**
     * @return mixed
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param mixed $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param mixed $sku
     */
    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    /**
     * @return mixed
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @param mixed $color
     */
    public function setColor($color)
    {
        $this->color = $color;
    }

    /**
     * @return mixed
     */
    public function getColor()
    {
        return $this->color;
    }
}
