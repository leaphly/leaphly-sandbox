<?php
namespace Acme\CartBundle\Entity;

use Leaphly\CartBundle\Model\Item as BaseItem;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * Acme\CartBundle\Entity
 *
 * @ORM\Table(name="cart_item")
 * @ORM\Entity()
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 *      "ticket"  = "Acme\Product\ConferenceBundle\Entity\TicketItem",
 *      "tShirt"  = "Acme\Product\TshirtBundle\Entity\TshirtItem"
 * })
 *
 * @ORM\HasLifecycleCallbacks()
 */
abstract class Item extends BaseItem
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

}
