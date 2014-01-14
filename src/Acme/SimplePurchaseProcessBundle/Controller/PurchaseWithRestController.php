<?php

namespace Acme\SimplePurchaseProcessBundle\Controller;

use Acme\SimplePurchaseProcessBundle\Type\CreditCardType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Leaphly\Cart\Transition\TransitionInterface;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;

class PurchaseWithRestController extends Controller
{
    /**
     * Step0 - Stores the cart in session, then requires user login or registration.
     */
    public function authenticateAction()
    {
        $cartId = $this->getRequest()->get('cart_id');
        // stores the cart in the session.
        $this->getRequest()->getSession()->set('cart_id', $cartId);
        // applies the 'order_start' transition to the cart,
        //  changing the state of the cart (this could fail and raise a http:406 exception).
        $url = sprintf('carts/%s/transitions/%s.json', $cartId, TransitionInterface::TRANSITION_ORDER_START);
        if (!$this->makeRequestViaHttpClient('post', $url)) {
            throw new NotAcceptableHttpException();
        }
        // forwards to the login form.
        return $this->forward('SimplePurchaseProcessBundle:Security:login',
            array(),
            array('redirect' => 'simple_purchase_process_rest_payment')
        );
    }

    /**
     * Step1 - Shows the payment form.
     */
    public function paymentAction()
    {
        $request = $this->getRequest();
        // gets the cart from the session
        $cart = $this->getCartFromSession();
        // patches the cart associating the user to the cart
        $url = sprintf('full/carts/%s.json', $cart['id']);
        $body = $this->get('jms_serializer')->serialize(array('identifier' => $this->getUser()->getUsername()), 'json');
        $patch = $this->makeRequestViaHttpClient('patch', $url, $body);

        if (!$cart['id']) {
            throw new \Exception('Something wrong, cart and user.');
        }

        // Applies the 'order_write' transition to the cart (this could fail and raise a http:406 exception).
        $url = sprintf('carts/%s/transitions/%s.json', $cart['id'], TransitionInterface::TRANSITION_ORDER_WRITE);
        if (!$this->makeRequestViaHttpClient('post', $url)) {
            throw new NotAcceptableHttpException();
        }
        // Creates the credit card form, and shows it.
        $form = $this->createForm(new CreditCardType());
        $targetPath = $this->get('router')->generate('simple_purchase_process_rest_make_payment', array(), true);

        return $this->render('SimplePurchaseProcessBundle:Purchase:creditCard.html.twig',
            array('formCreditCard' => $form->createView(), 'targetPath' => $targetPath)
        );
    }

    /**
     * Step2 - Makes a payment, handling the post of the payment form, and checking the state of the cart.
     */
    public function makePaymentAction()
    {
        // gets the cart from the session
        $cart = $this->getCartFromSession();
        // Creates the credit cart form.
        $form = $this->createForm(new CreditCardType())->handleRequest($this->getRequest());
        //if ($form->isValid()) Not used for this demo :)

        // Can the transition 'order_success' be applied on the cart (read-only)?
        $url = sprintf('carts/%s/transitions/%s.json', $cart['id'], TransitionInterface::TRANSITION_ORDER_SUCCESS);
        if ($this->makeRequestViaHttpClient('get', $url)) {
            // somethings useful and the does the payment with the $form
            // ...
            // applies the 'order_success' transition, changing state to the cart.
            $url = sprintf('carts/%s/transitions/%s.json', $cart['id'], TransitionInterface::TRANSITION_ORDER_SUCCESS);
            if (!$this->makeRequestViaHttpClient('post', $url)) {
                throw new NotAcceptableHttpException();
            }
            //redirect to the thank-you-page
            return $this->forward('SimplePurchaseProcessBundle:PurchaseWithRest:thankYouPage');
        }

        //redirect to the form.
        return $this->forward('SimplePurchaseProcessBundle:PurchaseWithRest:payment');
    }

    /**
     * Step3 - Thank you page.
     */
    public function thankYouPageAction()
    {
        $cart = $this->getCartFromSession();

        $url = sprintf('carts/%s/transitions/%s.json', $cart['id'], TransitionInterface::TRANSITION_ORDER_SUCCESS);
        if (!$this->makeRequestViaHttpClient('post', $url)) {
            throw new NotAcceptableHttpException();
        }

        return $this->render('SimplePurchaseProcessBundle:Purchase:thankyou.html.twig', array('cart' => $cart));
    }

    private function getCartFromSession()
    {
        $request = $this->getRequest();
        $session = $request->getSession();
        $cartId  = $session->get('cart_id');

        if (!$cartId) {
            // flash message error
            throw new \Exception('error on session');
        }

        return $this->getCart($cartId);
    }

    private function getCart($cartId)
    {
        $cart = $this->makeRequestViaHttpClient('get', sprintf('full/carts/%s.json', $cartId));

        return $cart;
    }

    private function makeRequestViaHttpClient($method, $path, $body = null, $headers = array())
    {
        $client = $this->get('acme.demo.http_client');

        $request = $client->{$method}(
            sprintf('%s/api/v1/%s', $this->container->getParameter('simple_purchase_process.leaphly_api_host'), $path),
            $headers,
            $body
        );
        $request->setAuth('consumer', 'consumer');
        $request->setPort($this->container->getParameter('simple_purchase_process.leaphly_api_port'));
        $response = $request->send();

        return $response->json();
    }
}
