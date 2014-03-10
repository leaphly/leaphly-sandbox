<?php

namespace Acme\SimplePurchaseProcessBundle\Tests\Controller;

use Acme\SimplePurchaseProcessBundle\Tests\Mock\MockedHttpClient;
use Acme\SimplePurchaseProcessBundle\Tests\WebTestCase;
use Guzzle\Http\Message\Response;
use Guzzle\Plugin\Mock\MockPlugin;
use Symfony\Bundle\FrameworkBundle\Client;

class PurchaseControllerTest extends WebTestCase
{

    protected $mockPlugin;
    protected $clientService;
    protected $lastCart;

    public function setUp()
    {
        parent::setUp();
        $this->mockPlugin = new MockPlugin();
    }

    /**
     * Feature: Purchase process
     * In order to buy products
     * As Customer
     * I want to be able to use Purchase process
     *
     * @dataProvider provider
     * @group functional
     */
    public function testCustomerShouldBeAbleToPerformThePurchaseProcess($url, $routeName, $env, $fixtures, $registryName)
    {
        $this->customSetUp($env, $fixtures, $registryName);
        $responses = $this->getFakeResponsesByUrl($url);
        foreach ($responses as $response) {
            MockedHttpClient::addResponse($response);
        }

        $this->startingThePurchaseProcessCustomerCouldPerformLoginAndThenShouldGoToPaymentPage($this->cart->getId(), $this->client, $url, $routeName);

        $this->loggedUserCouldPerformASuccessfulPayment();

        $this->UserShouldSeeTheThankYouPage();

        MockedHttpClient::resetResponses();
    }

    /**
     * as Anonymous customer
     * he could perform login
     * then he should be redirected to the Payment page.
     *
     * @param $cartId
     * @param Client $client
     * @param $url
     * @param $routeName
     */
    private function startingThePurchaseProcessCustomerCouldPerformLoginAndThenShouldGoToPaymentPage($cartId, Client $client, $url, $routeName)
    {
        $crawler = $client->request('GET', $url . '?cart_id=' . $cartId);
        $but = $crawler->selectButton('login');
        $form = $but->form(array('_username'=>'admin','_password'=>'admin' ), 'POST');
        $this->client->submit($form);
        // assert the login is done, redirection ...
        $this->assertEquals(
            302,
            $this->client->getResponse()->getStatusCode(),
            $this->client->getResponse()->getContent()
        );
        $this->client->followRedirect();

        $this->assertEquals($routeName, $this->client->getRequest()->get('_route'));

        $this->assertEquals(
            200,
            $this->client->getResponse()->getStatusCode(),
            $this->client->getResponse()->getContent()
        );
    }

    private function loggedUserCouldPerformASuccessfulPayment()
    {
        $crawler = $this->client->getCrawler();
        $but = $crawler->selectButton('credit_card[pay]');
        $form = $but->form(
            array(
                'credit_card[card_holder]'       =>'test',
                'credit_card[number]'            => 'aa',
                'credit_card[expiry_date_year]'  => date("Y"),
                'credit_card[expiry_date_month]' => '12',
                'credit_card[cvv]'               =>'admin',
            ), 'POST');
        $this->client->submit($form);
        // assert the login is done, redirection ...
        $this->assertEquals(
            200,
            $this->client->getResponse()->getStatusCode(),
            $this->client->getResponse()->getContent()
        );
    }

    private function UserShouldSeeTheThankYouPage()
    {
        $this->assertGreaterThan(
            0,
            $this->client->getCrawler()->filter('html:contains("thank you")')->count(),
            $this->client->getResponse()->getContent()
        );
    }

    public function getFakeCartAsArray()
    {
        return array(
            "id" => $this->cart->getId(),
            "identifier" =>"1",
            "expires_at" => "",
            "created_at" => "",
            "updated_at" => "",
            "items" => array(
                array(
                    "id" => "5257ec6af874e615658b4523",
                    "price" => 3,
                    "final_price" => 3
                ),
                array(
                    "id" => "5257ec6af874e615658b4568",
                    "price" => 3,
                    "final_price" => 3
                )
            ),
            "currency" => "EUR",
            "state" => "201",
            "price" => 7,
            "final_price" => 8,
            "customer" => 'test',
        );
    }

    public function getFakeResponsesByUrl($url)
    {
        $responses = array(
            '/purchase-process/authenticate' => array(
                new Response(200,array('contentType'=>'application/json'),json_encode(array(true))),
                new Response(200,array('contentType'=>'application/json'),json_encode(array(true)))
            ),
            '/purchase-process/rest/authenticate' => array(
                // post carts/5257f442f874e6aa6b8b4567/transitions/order_start.json
                new Response(201,array('contentType' => "application/json"), json_encode($this->getFakeCartAsArray())),
                //get full/carts/5257f6f8f874e68d6d8b4567.json
                new Response(200,array('contentType' => "application/json"), json_encode($this->getFakeCartAsArray())),
                //patch full/carts/5257f6f8f874e68d6d8b4567.json
                new Response(200,array('contentType' => "application/json"), json_encode($this->getFakeCartAsArray())),
                //post carts/5257f6f8f874e68d6d8b4567/transitions/order_write.json
                new Response(201,array('contentType' => "application/json"), json_encode($this->getFakeCartAsArray())),
                //get full/carts/5257f6f8f874e68d6d8b4567.json
                new Response(200,array('contentType' => "application/json"), json_encode($this->getFakeCartAsArray())),
                //get carts/5257f6f8f874e68d6d8b4567/transitions/order_success.json
                new Response(200,array('contentType' => "application/json"), json_encode(array(true))),
                //post carts/5257f6f8f874e68d6d8b4567/transitions/order_success.json
                new Response(201,array('contentType' => "application/json"), json_encode($this->getFakeCartAsArray())),
                //get full/carts/5257f6f8f874e68d6d8b4567.json
                new Response(200,array('contentType' => "application/json"), json_encode($this->getFakeCartAsArray())),
                //post carts/5257f6f8f874e68d6d8b4567/transitions/order_success.json
                new Response(201,array('contentType' => "application/json"), json_encode($this->getFakeCartAsArray())),
            )
        );

        return $responses[$url];
    }

    public function provider()
    {
        return array(
            'container Test' => array(
                '/purchase-process/authenticate',
                'simple_purchase_process_service_payment',
                'test',
                array(
                    'Acme\CartBundle\Tests\Fixture\Document\LoadCartData',
                    'Acme\CartBundle\Tests\Fixture\Document\LoadProductData'
                ),
                'doctrine_mongodb',
            ),
            'restful Test' => array(
                '/purchase-process/rest/authenticate',
                'simple_purchase_process_rest_payment',
                'test',
                array(
                    'Acme\CartBundle\Tests\Fixture\Document\LoadCartData',
                    'Acme\CartBundle\Tests\Fixture\Document\LoadProductData'
                ),
                'doctrine_mongodb'
            )
        );
    }
}
