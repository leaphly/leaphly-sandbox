<?php
namespace Acme\TestBundle\Tests\Controller;
use Acme\TestBundle\Tests\WebTestCase;

class CartsControllerTest extends WebTestCase
{
    /**
     * @dataProvider multiEnvProvider
     */
    public function testGetCartActionJson($env, $fixtures, $registryName)
    {
        $this->customSetUp($env, $fixtures, $registryName);
        $route =  $this->getUrl('api_1_get_cart', array('cart_id' => $this->cart->getId(), '_format' => 'json'));

        $this->client->request('GET', $route);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $content = $response->getContent();
       // $content = '{"id": 1}'; //expected
        $decoded = json_decode($content, true);
        $this->assertTrue(isset($decoded['id']));
    }

    /**
     * @dataProvider multiEnvProvider
     */
    public function testGetCartActionHtml($env, $fixtures, $registryName)
    {
        $this->customSetUp($env, $fixtures, $registryName);
        $route =  $this->getUrl('api_1_get_cart', array('cart_id' => $this->cart->getId()));
        $crawler = $this->client->request('GET', $route);
        $response = $this->client->getResponse();

        $this->assertTrue($crawler->filter('section[data-uid=\'' . $this->cart->getId() . '\']')->count() > 0, $response->getContent());
        $this->assertTrue($response->isOk(), $response->getContent());
    }

    /**
     * @dataProvider multiEnvProvider
     */
    public function testGetCartActionShouldResponse404WithJson($env, $fixtures, $registryName)
    {
        $this->customSetUp($env, $fixtures, $registryName);

        $route =  $this->getUrl('api_1_get_cart', array('cart_id' => '00000000272b8b8c0000000046b6f56d', '_format' => 'json'));
        $crawler = $this->client->request('GET', $route);
        $response = $this->client->getResponse();
        $this->assertEquals('404',  $response->getStatusCode(), $response->getContent());
    }

    /**
     * @dataProvider multiEnvProvider
     */
    public function testGetCartActionShouldResponse404WithHtml($env, $fixtures, $registryName)
    {
        $this->customSetUp($env, $fixtures, $registryName);

        $route =  $this->getUrl('api_1_get_cart', array('cart_id' => '999a'));
        $this->client->request('GET', $route);
        $response = $this->client->getResponse();

        $this->assertEquals('404',  $response->getStatusCode(), $response->getContent());
    }

    // ----------------------------------------------------------------------
    // POST Section
    // ----------------------------------------------------------------------
    /**
     * @dataProvider multiEnvProvider
     */
    public function testPostCartActionJsonWithAdminRole($env, $fixtures, $registryName)
    {
        $this->customSetUp($env, $fixtures, $registryName);
        $this->adminLogIn($this->client);
        $cart = $this->getContainer()->get('leaphly_cart.cart_manager')->createCart();

        $identifier = 'leaphly01';
        $cart->setIdentifier($identifier);

        $route =  $this->getUrl('api_1_full_post_cart', array('_format' => 'json'));

        $serializer = $this->getContainer()->get('jms_serializer');
        $data = $serializer->serialize($cart, 'json');

        $this->client->request(
            'POST',
            $route,
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            $data
        );

        $this->assertJsonResponse($this->client->getResponse(), 201, false);
        $cart2 = $this->getContainer()->get('leaphly_cart.cart_manager')->findCartBy(array('identifier' => $identifier));
        $this->assertNotNull($cart2);

        // cleaning stuff
        $this->getContainer()->get('leaphly_cart.cart_manager')->deleteCart($cart);
    }

    /**
     * @dataProvider multiEnvProvider
     */
    public function testPostCartActionJsonWithNoAdminRole($env, $fixtures, $registryName)
    {
        $this->customSetUp($env, $fixtures, $registryName);

        $cart = $this->getContainer()->get('leaphly_cart.cart_manager')->createCart();
        $identifier = 'leaphly01';
        $cart->setIdentifier($identifier);
        $cart->setCurrency(null);
        $route =  $this->getUrl('api_1_post_cart', array('_format' => 'json'));
        $serializer = $this->getContainer()->get('jms_serializer');
        $data = $serializer->serialize($cart, 'json');
        $this->client->request(
            'POST',
            $route,
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            $data
        );
        $this->assertJsonResponse($this->client->getResponse(), 201, false);
        $cart2 = $this->getContainer()->get('leaphly_cart.cart_manager')->findCartBy(array('identifier' => $identifier));
        $this->assertNotNull($cart2);
        // cleaning stuff
        $this->getContainer()->get('leaphly_cart.cart_manager')->deleteCart($cart);
    }

    // ----------------------------------------------------------------------
    // DELETE Section
    // ----------------------------------------------------------------------
    /**
     * @dataProvider multiEnvProvider
     */
    public function testDeleteCartActionJson($env, $fixtures, $registryName)
    {
        $this->customSetUp($env, $fixtures, $registryName);

        $route =  $this->getUrl('api_1_delete_cart', array('cart_id' => $this->cart->getId(), '_format' => 'json'));
        $this->client->request('DELETE', $route);

        $this->assertJsonResponse($this->client->getResponse(), 204, false);
    }

    // ----------------------------------------------------------------------
    // PUT Section
    // ----------------------------------------------------------------------
    /**
     * @dataProvider multiEnvProvider
     */
    public function testPutCartActionJsonWithAdminRole($env, $fixtures, $registryName)
    {
        $this->customSetUp($env, $fixtures, $registryName);
        $this->adminLogIn($this->client);
        $route =  $this->getUrl('api_1_full_put_cart', array('cart_id' => $this->cart->getId(), '_format' => 'json'));
        $identifier = 'leaphly01';
        $this->cart->setIdentifier($identifier);

        $serializer = $this->getContainer()->get('jms_serializer');
        $data = $serializer->serialize($this->cart, 'json');

        $this->client->request(
            'PUT',
            $route,
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            $data
        );

        $this->assertJsonResponse($this->client->getResponse(), 204, false);

        $cart = $this->getContainer()->get('leaphly_cart.cart_manager')->findCartBy(array('identifier' => $identifier));
        $this->assertNotNull($cart);

    }

    /**
     * @dataProvider multiEnvProvider
     */
    public function testPutCartActionJsonWithNoAdminRole($env, $fixtures, $registryName)
    {
        $this->customSetUp($env, $fixtures, $registryName);
        $identifier = 'leaphly0a1';
        $parameters = array('identifier' => $identifier);
        $route =  $this->getUrl('api_1_put_cart', array('cart_id' => $this->cart->getId(), '_format' => 'json'));

        $serializer = $this->getContainer()->get('jms_serializer');
        $data = $serializer->serialize($parameters, 'json');

        $this->client->request(
            'PUT',
            $route,
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            $data
        );

        $this->assertJsonResponse($this->client->getResponse(), 204, false);

        $cart = $this->getContainer()->get('leaphly_cart.cart_manager')->findCartBy(array('identifier' => $identifier));
        $this->assertNotNull($cart);
    }

    /**
     * @dataProvider multiEnvProvider
     */
    public function testPatchCartActionJsonWithNoAdminRole($env, $fixtures, $registryName)
    {
        $this->customSetUp($env, $fixtures, $registryName);
        $identifier = 'leaphly0a1';
        $parameters = array('identifier' => $identifier);
        $route =  $this->getUrl('api_1_patch_cart', array('cart_id' => $this->cart->getId(), '_format' => 'json'));

        $serializer = $this->getContainer()->get('jms_serializer');
        $data = $serializer->serialize($parameters, 'json');

        $this->client->request(
            'PATCH',
            $route,
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            $data
        );

        $this->assertJsonResponse($this->client->getResponse(), 204, false);

        $cart = $this->getContainer()->get('leaphly_cart.cart_manager')->findCartBy(array('identifier' => $identifier));
        $this->assertNotNull($cart);
    }

    /**
     * @dataProvider multiEnvProvider
     */
    public function testPatchCartActionJsonWithAdminRole($env, $fixtures, $registryName)
    {
        $this->customSetUp($env, $fixtures, $registryName);
        $this->adminLogIn($this->client);
        $expireDate = new \DateTime("now + 1 week");

        $parameters = array('expiresAt' => $expireDate);
        $route =  $this->getUrl('api_1_full_patch_cart', array('cart_id' => $this->cart->getId(), '_format' => 'json'));

        $serializer = $this->getContainer()->get('jms_serializer');
        $data = $serializer->serialize($parameters, 'json');

        $this->client->request(
            'PATCH',
            $route,
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            $data
        );

        $this->assertJsonResponse($this->client->getResponse(), 204, false);

        $cart = $this->getContainer()->get('leaphly_cart.cart_manager')->findCartBy(array('id' => $this->cart->getId(), 'expiresAt' => $expireDate));
        $this->assertNotNull($cart);
    }

    public function provideInvalidJsonCarts()
    {
        return array(
           /* 'mongo_test invalid date'       => array(
                'env' => 'test',
                'fixture' => array('Acme\TestBundle\Tests\Fixture\Document\LoadCartData'),
                'registry_name' => 'doctrine_mongodb',
                'cart' => '{"id":"5226fd34f874e6d91a8b4567","identifier":"1","expiresAt":"invalid date","createdAt":"invalid date","updatedAt":"2013-09-04T11:28:20+0204","items":[{"id":"5226fd34f874e6d91a8b4568","name":"Shoes"},{"id":"5226fd34f874e6d91a8b4569","name":"Tshirt"}],"currency":"invalid currency","state":100}'

            ),
            'mongo_test invalid status'     => array(
                'env' => 'test',
                'fixture' => array('Acme\TestBundle\Tests\Fixture\Document\LoadCartData'),
                'registry_name' => 'doctrine_mongodb',
                'cart' => '{"id":"5226fd34f874e6d91a8b4567","identifier":"1","expiresAt":"2013-09-05T11:28:20+0200","createdAt":"2013-09-03T11:28:20+0200","updatedAt":"2013-09-04T11:28:20+0200","items":[{"id":"5226fd34f874e6d91a8b4568","name":"Shoes"},{"id":"5226fd34f874e6d91a8b4569","name":"Tshirt"}],"currency":"EUR","state":0}'
            ),
            'mongo_test invalid currency' => array(
                'env' => 'test',
                'fixture' => array('Acme\TestBundle\Tests\Fixture\Document\LoadCartData'),
                'registry_name' => 'doctrine_mongodb',
                'cart' => '{"id":"5226fd34f874e6d91a8b4567","identifier": "1","expiresAt":"2013-09-05T11:28:20+0200","createdAt":"2013-09-03T11:28:20+0200","updatedAt":"2013-09-04T11:28:20+0200","items":[{"id":"5226fd34f874e6d91a8b4568","name":"Shoes"},{"id":"5226fd34f874e6d91a8b4569","name":"Tshirt"}],"currency":"99","state":100}'
            ),*/
            'orm_test invalid date'         => array(
                'env' => 'orm_test',
                'fixture' => array('Acme\TestBundle\Tests\Fixture\Entity\LoadCartData'),
                'registry_name' => 'doctrine',
                'cart' => '{"identifier":"1","items":[{"name":"Shoes"},{"name":"Tshirt"}],"currency":"invalid currency","state":"invalid state"}'
            ),
            'orm_test invalid status'       => array(
                'env' => 'orm_test',
                'fixture' => array('Acme\TestBundle\Tests\Fixture\Entity\LoadCartData'),
                'registry_name' => 'doctrine',
                'cart' => '{"identifier":"1","expiresAt":"2013-09-05T11:28:20+0200","createdAt":"2013-09-03T11:28:20+0200","updatedAt":"2013-09-04T11:28:20+0200","items":[{"name":"Shoes"},{"name":"Tshirt"}],"currency":"EUR","state":"invalid"}'
            ),
            'orm_test invalid currency'   => array(
                'env' => 'orm_test',
                'fixture' => array('Acme\TestBundle\Tests\Fixture\Entity\LoadCartData'),
                'registry_name' => 'doctrine',
                'cart' => '{"identifier": "1","expiresAt":"2013-09-05T11:28:20+0200","createdAt":"2013-09-03T11:28:20+0200","updatedAt":"2013-09-04T11:28:20+0200","items":[{"name":"Shoes"},{"name":"Tshirt"}],"currency":"99","state":open}'
            ),
        );
    }

}
