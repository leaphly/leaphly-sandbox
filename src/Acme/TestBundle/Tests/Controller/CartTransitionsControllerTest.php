<?php
namespace Acme\TestBundle\Tests\Controller;

use Acme\TestBundle\Tests\WebTestCase;
use Leaphly\Cart\Transition\TransitionInterface;

class CartTransitionsControllerTest extends WebTestCase
{
    /**
     * @dataProvider multiEnvProvider
     */
    public function testPostCartTransitionAction($env, $fixtures, $registryName)
    {
        $this->customSetUp($env, $fixtures, $registryName);
        $this->adminLogIn($this->client);
        $route =  $this->getUrl('api_1_post_cart_transition',
            array(
                'cart_id' => $this->cart->getId(),
                'transition'=> TransitionInterface::TRANSITION_CART_WRITE ,
                '_format' => 'json'
            )
        );

        $this->client->request(
            'POST',
            $route,
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json')
        );

        $this->assertJsonResponse($this->client->getResponse(), 201, false);
        $cart = $this->getContainer()->get('leaphly_cart.cart_manager')->find($this->cartId);
        $this->assertNotNull($cart);
        $this->assertEquals(101, $cart->getFiniteState());
    }

    /**
     * @dataProvider multiEnvProvider
     */
    public function testPostCartTransitionFailAction($env, $fixtures, $registryName)
    {
        $this->customSetUp($env, $fixtures, $registryName);
        $this->adminLogIn($this->client);
        $route =  $this->getUrl('api_1_post_cart_transition',
            array('cart_id' => $this->cart->getId(),
                'transition'=> TransitionInterface::TRANSITION_ORDER_SUCCESS ,
                '_format' => 'json')
        );
        $this->client->request(
            'POST',
            $route,
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json')
        );

        $this->assertJsonResponse($this->client->getResponse(), 406, false);
        $cart = $this->getContainer()->get('leaphly_cart.cart_manager')->find($this->cartId);
    }

    /**
     * @dataProvider multiEnvProvider
     */
    public function testGetCartTransactionAction($env, $fixtures, $registryName)
    {
        $this->customSetUp($env, $fixtures, $registryName);
        $this->adminLogIn($this->client);
        $route =  $this->getUrl(
            'api_1_get_cart_transition',
            array(
                'cart_id' => $this->cart->getId(),
                'transition'=> TransitionInterface::TRANSITION_CART_WRITE,
                '_format' => 'html'
            )
        );

        $crawler = $this->client->request('GET', $route);
        $response = $this->client->getResponse();
        $c = $this->client->getResponse();
        $this->assertEquals('200',  $response->getStatusCode(), $response->getContent());
    }

    /**
     * @dataProvider multiEnvProvider
     */
    public function testFailGetCartTransactionAction($env, $fixtures, $registryName)
    {
        $this->customSetUp($env, $fixtures, $registryName);
        $this->adminLogIn($this->client);
        $route =  $this->getUrl(
            'api_1_get_cart_transition',
            array(
                'cart_id' => $this->cart->getId(),
                'transition'=> TransitionInterface::TRANSITION_ORDER_SUCCESS,
                '_format' => 'json'
            )
        );

        $crawler = $this->client->request('GET', $route);
        $response = $this->client->getResponse();

        $this->assertEquals('406',  $response->getStatusCode(), $response->getContent());
    }
}
