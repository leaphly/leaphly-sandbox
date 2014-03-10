<?php
namespace Acme\TestBundle\Tests\Controller;

use Acme\TestBundle\Fixture\FixtureCollector;

use Acme\TestBundle\Tests\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

class CartItemsControllerTest extends WebTestCase
{
    /**
     * @dataProvider multiEnvProvider
     */
    public function testDeleteCartItemActionJson($env, $fixture, $registryName)
    {
        $this->environment = $env;
        $client = static::createClient(array('environment' => $this->environment));
        $this->loadFixtures($fixture, null, $registryName);
        $cart = FixtureCollector::getCarts($registryName);
        /** @var \Acme\CartBundle\Entity\Cart $cart */
        $cart = array_pop($cart);

        $item = $cart->getItems();
        $route =  $this->getUrl('api_1_delete_cart_item', array('cart_id' => $cart->getId(), 'item_id'=> $item[0]->getId(),'_format' => 'json'));
        $client->request('DELETE', $route);
        $this->assertJsonResponse($client->getResponse(), 200, false);
    }

    /**
     * @dataProvider multiEnvProvider
     */
    public function testDeleteCartItemAction($env, $fixtures, $registryName)
    {
        $this->customSetUp($env, $fixtures, $registryName);

        $route =  $this->getUrl('api_1_delete_cart_items', array('cart_id' => $this->cart->getId()));
        $this->client->request('DELETE', $route);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode(), $response->getStatusCode());
    }

    /**
     * @dataProvider multiEnvProvider
     */
    public function testDeleteAllCartItemsActionJson($env, $fixture, $registryName)
    {
        $this->customSetUp($env, $fixture, $registryName);

        $route =  $this->getUrl('api_1_delete_cart_items', array('cart_id' => $this->cart->getId(), '_format' => 'json'));
        $this->client->request('DELETE', $route);
        $this->assertJsonResponse($this->client->getResponse(), 200, false);
    }

    /**
     * @dataProvider multiEnvProvider
     */
    public function testDeleteAllCartItemsAction($env, $fixtures, $registryName)
    {
        $this->customSetUp($env, $fixtures, $registryName);

        $route =  $this->getUrl('api_1_delete_cart_item', array('cart_id' => $this->cart->getId(), 'item_id'=> $this->items[0]->getId()));
        $this->client->request('DELETE', $route);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode(), $response->getStatusCode());
    }

    /**
     * @dataProvider multiEnvProvider
     */
    public function testPostCartItemAction($env, $fixtures, $registryName)
    {
        $this->customSetUp($env, $fixtures, $registryName);

        $route =  $this->getUrl('api_1_post_cart_item', array('cart_id' => $this->cart->getId(),  '_format' => 'json'));

        $request = array(
            'product' => $this->product,
            'color'=>'blue',
            'sku' => 'S',
            'quantity' => 2
        );

        $serializer = $this->getContainer()->get('jms_serializer');
        $data = $serializer->serialize($request, 'json');

        $this->client->request(
            'POST',
            $route,
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            $data
        );

        $this->assertJsonResponse($this->client->getResponse(), 201, false);
        $cart = $this->getContainer()->get('leaphly_cart.cart_manager')->find($this->cartId);

        $this->assertNotNull($cart);
    }

    /**
     * @dataProvider multiEnvProvider
     */
    public function testPatchCartItemAction($env, $fixtures, $registryName)
    {

        $this->customSetUp($env, $fixtures, $registryName);
        $item = $this->items[1];

        $route =  $this->getUrl(
            'api_1_patch_cart_item',
            array('cart_id' => $this->cart->getId(), 'item_id' => $item->getId(),  '_format' => 'json')
        );

        $request = array(
            'product' => $this->product,
            'color'=>'blue',
            'sku' => 'S',
            'quantity' => 2
        );
        $serializer = $this->getContainer()->get('jms_serializer');
        $data = $serializer->serialize($request, 'json');

        $this->client->request(
            'PATCH',
            $route,
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            $data
        );

        $this->assertJsonResponse($this->client->getResponse(), 200, false);
        $cart = $this->getContainer()->get('leaphly_cart.cart_manager')->find($this->cartId);

        $this->assertNotNull($cart);
    }

    /**
     * @dataProvider multiEnvProvider
     */
    public function testPostCartItemFullAccessAction($env, $fixtures, $registryName)
    {
        $this->customSetUp($env, $fixtures, $registryName);
        $this->adminLogIn($this->client);
        $route =  $this->getUrl('api_1_full_post_cart_item', array('cart_id' => $this->cart->getId(),  '_format' => 'json'));
        $request = array(
            'product' => $this->product,
            'color'=>'blue',
            'name' => 'a name'
        );
        $serializer = $this->getContainer()->get('jms_serializer');
        $data = $serializer->serialize($request, 'json');

        $this->client->request(
            'POST',
            $route,
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            $data
        );

        $this->assertJsonResponse($this->client->getResponse(), 201, false);
        $cart = $this->getContainer()->get('leaphly_cart.cart_manager')->find($this->cartId);
        $this->assertNotNull($cart);
    }

    /**
     * @dataProvider multiEnvProvider
     */
    public function testFailingPostCartItemLimitedAccessAction($env, $fixtures, $registryName)
    {
        $this->customSetUp($env, $fixtures, $registryName);
        $route =  $this->getUrl('api_1_post_cart_item', array('cart_id' => $this->cart->getId(),  '_format' => 'json'));
        $request = array(
            'product' => $this->product,
            'color'=>'blue',
            'name' => 'a name',
            'sku' => 'S',
            'quantity' => 2,
            'price' => 100.00
        );
        $serializer = $this->getContainer()->get('jms_serializer');
        $data = $serializer->serialize($request, 'json');

        $this->client->request(
            'POST',
            $route,
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            $data
        );

        $this->assertJsonResponse($this->client->getResponse(), 422, false);
        $errors = json_decode($this->client->getResponse()->getContent(),true);
        $this->assertArrayHasKey('errors', $errors);
        $this->assertEquals('This form should not contain extra fields.', $errors['errors'][0]);
    }
}
