<?php

namespace Acme\SimplePurchaseProcessBundle\Tests\Fixture;

/**
 * Class FixtureCollector, just a fixture container, useful when you need the Id of the entity inserted.
 */
final class FixtureCollector
{

    const MONGODB = 'doctrine_mongodb';
    const ORM = 'doctrine';

    private static $carts = array();

    public static function addCart($doctrine, $cart)
    {
        self::$carts[$doctrine][] = $cart;
    }

    /**
     * @param $doctrine
     * @return \Acme\CartBundle\Entity\Cart
     */
    public static function getCarts($doctrine)
    {
        return self::$carts[$doctrine];
    }
}
