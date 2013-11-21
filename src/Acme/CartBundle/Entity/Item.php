<?php
namespace Acme\CartBundle\Entity;

use Leaphly\Cart\Model\Item as BaseItem;
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
}
