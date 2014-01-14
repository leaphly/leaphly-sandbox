<?php
namespace Acme\Product\TshirtBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Acme\CartBundle\Entity\Item as BaseItem;

/**
 * @ORM\Table(name="cart_item_tshirt")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class TshirtItem extends BaseItem
{
    /**
     * @var string $color
     * @ORM\Column(type="string", nullable=true)
     */
    protected $color;

    /**
     * @var string $sku
     * @ORM\Column(type="string", nullable=true)
     */
    protected $sku;

    /**
     * @var integer $quantity
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $quantity;

    /**
     * @var \Acme\Product\TshirtBundle\Entity\TshirtProduct $product
     * @ORM\ManyToOne(targetEntity="TshirtProduct")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    protected $product;

    /**
     * @param string $color
     */
    public function setColor($color)
    {
        $this->color = $color;
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param \Acme\Product\TshirtBundle\Entity\TshirtProduct $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
    }

    /**
     * @return \Acme\Product\TshirtBundle\Entity\TshirtProduct
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
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

}
