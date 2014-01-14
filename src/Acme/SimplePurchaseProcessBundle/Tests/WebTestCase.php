<?php
namespace Acme\SimplePurchaseProcessBundle\Tests;

use Acme\CartBundle\Tests\Fixture\FixtureCollector;
use Liip\FunctionalTestBundle\Test\WebTestCase as BaseWebTestCase;

class WebTestCase extends BaseWebTestCase
{
    protected $product;
    protected $productId;
    protected $cart;
    protected $cartId;
    protected $items;

    protected $client;

    public function customSetUp($env, $fixtures, $registryName)
    {
        $this->environment = $env;
        $this->client = static::createClient(array('environment'=>$this->environment));
        $this->loadFixtures($fixtures, null, $registryName);
        $cart = FixtureCollector::getCarts($registryName);
        $this->cart = array_pop($cart);
    }
}
