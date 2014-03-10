<?php

namespace Acme\TestBundle\Fixture;

/**
 * Class FixtureCollector, just a fixture container, useful when you need the Id of the entity inserted.
 */
final class FixtureCollector
{
    const MONGODB = 'doctrine_mongodb';
    const ORM = 'doctrine';

    private static $carts = array();
    private static $products = array();

    public static function addCart($doctrine, $cart)
    {
        self::$carts[$doctrine][] = $cart;
    }

    public static function addProduct($doctrine, $item)
    {
        self::$products[$doctrine][] = $item;
    }

    /**
     * @param $doctrine
     * @return \Acme\CartBundle\Entity\Cart
     */
    public static function getCarts($doctrine)
    {
        return self::$carts[$doctrine];
    }

    public static function getProducts($doctrine)
    {
        $products = isset(self::$products[$doctrine])? self::$products[$doctrine] : array();

        return $products;
    }
}
