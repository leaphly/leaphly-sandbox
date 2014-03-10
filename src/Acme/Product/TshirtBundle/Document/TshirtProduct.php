<?php

namespace Acme\Product\TshirtBundle\Document;

use Acme\CartBundle\Document\Product as BaseProduct;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class TshirtProduct extends BaseProduct
{
    /**
     * @var
     * @MongoDB\Field(type="string")
     */
    protected $sku;

    public function __construct()
    {
        $this->setName('tshirt');
        // your own logic
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

    public function getType()
    {
        return 'tshirt';
    }
}
