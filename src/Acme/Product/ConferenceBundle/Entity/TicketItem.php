<?php
namespace Acme\Product\ConferenceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Acme\CartBundle\Entity\Item as BaseItem;

/**
 * @ORM\Table(name="cart_item_ticket")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class TicketItem extends BaseItem
{
    /**
     * @var string $conferenceName
     * @ORM\Column(type="string", nullable=true)
     */
    protected $conferenceName;

    /**
     * @var \Datetime $when
     * @ORM\Column(type="datetime", nullable=true, name="when_date")
     */
    protected $when;

    /**
     * @var string $position
     * @ORM\Column(type="string", nullable=true)
     */
    protected $position;

    /**
     * @var integer $adults
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $adults;

    /**
     * @var integer $children
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $children;

    /**
     * @var \Acme\Product\ConferenceBundle\Entity\TicketProduct $product
     * @ORM\ManyToOne(targetEntity="TicketProduct")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    protected $product;

    /**
     * @param int $adults
     */
    public function setAdults($adults)
    {
        $this->adults = $adults;
    }

    /**
     * @return int
     */
    public function getAdults()
    {
        return $this->adults;
    }

    /**
     * @param int $children
     */
    public function setChildren($children)
    {
        $this->children = $children;
    }

    /**
     * @return int
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param string $conferenceName
     */
    public function setConferenceName($conferenceName)
    {
        $this->conferenceName = $conferenceName;
    }

    /**
     * @return string
     */
    public function getConferenceName()
    {
        return $this->conferenceName;
    }

    /**
     * @param string $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param \Acme\Product\ConferenceBundle\Entity\TicketProduct $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
    }

    /**
     * @return \Acme\Product\ConferenceBundle\Entity\TicketProduct
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param \Datetime $when
     */
    public function setWhen($when)
    {
        $this->when = $when;
    }

    /**
     * @return \Datetime
     */
    public function getWhen()
    {
        return $this->when;
    }

}
