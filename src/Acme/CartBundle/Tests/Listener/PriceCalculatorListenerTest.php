<?php
namespace Acme\CartBundle\Tests\Controller;

use Acme\CartBundle\Tests\WebTestCase;

class PriceCalculatorListenerTest extends WebTestCase
{
    /**
     * @dataProvider multiEnvProvider
     */
    public function testListenerShouldAutomaticallySetTheCartPrice($env, $fixtures, $registryName)
    {
        $this->customSetUp($env, $fixtures, $registryName);
        $this->cart->setPrice(0);
        $this->cart->setFinalPrice(0);

        $cart = $this->getContainer()->get('leaphly_cart.cart.full.handler')->putCart($this->cart, array('customer' => 'a'));

        $this->assertGreaterThan(0, $cart->getPrice());
        $this->assertGreaterThan(0, $cart->getFinalPrice());
    }

    /**
     * @dataProvider multiEnvProvider
     */
    public function testListenerShouldAutomaticallySetTheCartPriceViaRest($env, $fixtures, $registryName)
    {
        $this->customSetUp($env, $fixtures, $registryName);
        $this->adminLogIn($this->client);

        $parameters = array('customer' => 'a', 'price' => 0, 'finalPrice' => 0);

        $route =  $this->getUrl('api_1_full_put_cart', array('cart_id' => $this->cart->getId(), '_format' => 'json'));

        $serializer = $this->getContainer()->get('jms_serializer');
        $data = $serializer->serialize($parameters, 'json');

        $crawler = $this->client->request(
            'PUT',
            $route,
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            $data
        );

        $cart = $this->getContainer()->get('leaphly_cart.cart_manager')->findCartBy(array('id' => $this->cart->getId()));

        $this->assertGreaterThan(0, $cart->getPrice());
        $this->assertGreaterThan(0, $cart->getFinalPrice());
    }
}
