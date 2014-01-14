<?php
namespace Acme\TestBundle\Tests;

use Acme\TestBundle\Fixture\FixtureCollector;
use Liip\FunctionalTestBundle\Test\WebTestCase as BaseWebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class WebTestCase extends BaseWebTestCase
{
    protected $product;
    protected $productId;
    protected $cart;
    protected $cartId;
    protected $items;

    protected $client;

    protected function assertJsonResponse($response, $statusCode = 200, $checkValidJson =  true)
    {
        $this->assertEquals(
            $statusCode, $response->getStatusCode(),
            $response->getContent()
        );
        $this->assertTrue(
            $response->headers->contains('Content-Type', 'application/json'),
            $response->headers
        );

        if ($checkValidJson) {
            $decode = json_decode($response->getContent());
            $this->assertTrue(($decode != null && $decode != false),
                'is response valid json: [' . $response->getContent() . ']'
            );
        }
    }

    protected function adminLogIn(Client $client)
    {
        $session = $client->getContainer()->get('session');

        $firewall = 'secured_area';
        $token = new UsernamePasswordToken('admin', null, $firewall, array('ROLE_ADMIN'));
        $session->set('_security_'.$firewall, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $client->getCookieJar()->set($cookie);
    }

    public function customSetUp($env, $fixtures, $registryName)
    {
        $this->environment = $env;
        $this->client = static::createClient(array('environment' => $this->environment));
        $this->loadFixtures($fixtures, null, $registryName);
        $cart = FixtureCollector::getCarts($registryName);
        $this->cart = array_pop($cart);
        $this->cartId = $this->cart->getId();
        $this->items = $this->cart->getItems();
        $productId = FixtureCollector::getProducts($registryName);
        $this->product = array_pop($productId);
    }

    public function multiEnvProvider()
    {
        return array(
            array(
                'env' => 'test',
                'fixture' => array(
                    'Acme\TestBundle\Fixture\Document\LoadCartData',
                    'Acme\TestBundle\Fixture\Document\LoadProductData'),
                'registry_name' => 'doctrine_mongodb'
            ),
            array(
               'env' => 'orm_test',
               'fixture' => array(
                   'Acme\TestBundle\Fixture\Entity\LoadCartData',
                   'Acme\TestBundle\Fixture\Entity\LoadProductData'),
               'registry_name' => 'doctrine'
           ),
        );
    }

}
