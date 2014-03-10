<?php
namespace Acme\CartBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use Leaphly\Cart\Model\Cart as BaseCart;

/**
 * @ORM\Entity
 * @ORM\Table(name="cart")
 */
class Cart extends BaseCart
{
    /**
     * @ORM\ManyToMany(targetEntity="Item", cascade={"all"})
     * @ORM\JoinTable(name="cart_items",
     *      joinColumns={@ORM\JoinColumn(name="cart_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="item_id", referencedColumnName="id", unique=true, onDelete="CASCADE")}
     *      )
     **/
    protected $items;

    /**
     * @ORM\Column(type="string", name="customer", nullable=true)
     */
    protected $customer;

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->currency = 'EUR';
    }

    public function __toString()
    {
        return sprintf('#%s', $this->getId());
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
