<?php

namespace Acme\Product\TshirtBundle\Entity;

use Acme\CartBundle\Entity\Product as BaseProduct;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class TshirtProduct extends BaseProduct
{
    /**
     * @var string $sku
     * @ORM\Column(type="string")
     */
    protected $sku;

    public function __construct()
    {
        $this->setName('tshirt');
        // your own logic
    }

    /**
     * @param string $sku
     */
    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    /**
     * @return string
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
