<?php

namespace Acme\Product\ConferenceBundle\Document;

use Leaphly\Cart\Model\Item as BaseItem;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use JMS\Serializer\Annotation\Type;

/**
 * @MongoDB\EmbeddedDocument
 */
class TicketItem extends BaseItem
{
    /**
     * @MongoDB\Id(strategy="auto")
     */
    protected $id;

    /**
     * @var
     * @MongoDB\Field(type="string")
     */
    protected $conferenceName;

    /**
     * @var
     * @Type("DateTime<'Y/m/d'>")
     * @MongoDB\Field(type="date")
     */
    protected $when;

    /**
     * @var
     * @MongoDB\Field(type="string")
     */
    protected $position;

    /**
     * @var
     * @MongoDB\Field(type="int")
     */
    protected $adults;

    /**
     * @var
     * @MongoDB\Field(type="int")
     */
    protected $children;

    /**
     * @var
     * @MongoDB\ReferenceOne(targetDocument="TicketProduct")
     */
    protected $product;

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
     * @param mixed $adults
     */
    public function setAdults($adults)
    {
        $this->adults = $adults;
    }

    /**
     * @return mixed
     */
    public function getAdults()
    {
        return $this->adults;
    }

    /**
     * @param mixed $children
     */
    public function setChildren($children)
    {
        $this->children = $children;
    }

    /**
     * @return mixed
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param mixed $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $when
     */
    public function setWhen($when)
    {
        $this->when = $when;
    }

    /**
     * @return mixed
     */
    public function getWhen()
    {
        return $this->when;
    }

    /**
     * @param string $conferenceName
     */
    public function setConferenceName($conferenceName)
    {
        $this->conferenceName = $conferenceName;
    }

    /**
     * @return mixed
     */
    public function getConferenceName()
    {
        return $this->conferenceName;
    }
}
