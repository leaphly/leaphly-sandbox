<?php

namespace Acme\SimplePurchaseProcessBundle\Controller;

use Acme\CartBundle\Entity\Cart;
use Acme\SimplePurchaseProcessBundle\Type\CreditCardType;
use Leaphly\Cart\Form\Exception\InvalidFormException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Leaphly\Cart\Transition\TransitionInterface;

class PurchaseWithServiceController extends Controller
{
    /**
     * Step0 - Store the cart in session, then require user login or registration.
     */
    public function authenticateAction()
    {
        $cartId = $this->getRequest()->get('cart_id');
        // stores the cart in the session.
        $this->getRequest()->getSession()->set('cart_id', $cartId);
        // fetches the cart object
        $cart = $this->getCart($cartId);
        // applies the 'order_start' transition to the cart,
        //  changing the state of the cart (this could fail and raise a http:406 exception).
        $this->get('leaphly_cart.cart.transition')
            ->apply($cart, TransitionInterface::TRANSITION_ORDER_START, TransitionInterface::FLUSH);
        // forwards to the login form.
        return $this->forward('SimplePurchaseProcessBundle:Security:login',
            array(),
            array('redirect' => 'simple_purchase_process_service_payment')
        );
    }

    /**
     * Step1 - Shows the payment form.
     */
    public function paymentAction()
    {
        // retrieves the cart id from the session and then fetches it via Service Container
        $cart = $this->getCartFromSession();

        try {
            // patches the cart associating the user to the cart
            $cart = $this->get('leaphly_cart.cart.full.handler')
                ->patchCart($cart, array('customer' => $this->getUser()->getUsername()));
        } catch (InvalidFormException $exception) {
            throw new \Exception('Something wrong, associating cart to user.', 500, $exception);
        }

        // Applies the 'order_write' transition to the cart (this could fail and raise a http:406 exception.
        $this->get('leaphly_cart.cart.transition')
            ->apply($cart, TransitionInterface::TRANSITION_ORDER_WRITE, TransitionInterface::FLUSH);

        // Creates the credit card form, and shows it.
        $form = $this->createForm(new CreditCardType());
        $targetPath = $this->get('router')->generate('simple_purchase_process_service_make_payment', array(), true);

        return $this->render('SimplePurchaseProcessBundle:Purchase:creditCard.html.twig',
            array('formCreditCard' => $form->createView(), 'targetPath' => $targetPath)
        );
    }

    /**
     * Step2 - Makes a payment, handling the post of the payment form, and checking the state of the cart.
     */
    public function makePaymentAction()
    {
        // Retrieves the Cartid from the session and then fetches it via Service Container
        $cart = $this->getCartFromSession();
        // Creates the credit cart form.
        $form = $this->createForm(new CreditCardType())->handleRequest($this->getRequest());
        //if ($form->isValid()) Not used for this demo :)

        // Can the transition 'order_success' be applied on the cart (read-only)?
        if ($this->get('leaphly_cart.cart.transition')
            ->can($cart, TransitionInterface::TRANSITION_ORDER_SUCCESS)) {

            // Somethings useful, then does the payment with the $form
            // ...

            // applies the 'order_success' transition, changing state to the cart.
            $this->get('leaphly_cart.cart.transition')
                ->apply($cart, TransitionInterface::TRANSITION_ORDER_SUCCESS, TransitionInterface::FLUSH);

            //redirects to the thank-you-page
            return $this->forward('SimplePurchaseProcessBundle:PurchaseWithService:thankYouPage');
        }

        //redirects to the form.
        return $this->forward('SimplePurchaseProcessBundle:Purchase:payment');
    }

    /**
     * Step3 - Thank you page.
     */
    public function thankYouPageAction()
    {
        // Retrieves the Cartid from the session and then fetches it via Service Container
        $cart = $this->getCartFromSession();

        // Applies the 'order_success' transition to the cart
        //  reloading of this page will rise a http:406 exception
        $this->get('leaphly_cart.cart.transition')->apply($cart, 'order_success');

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
        $cartManager = $this->get('leaphly_cart.cart_manager');
        $cart = $cartManager->find($cartId);

        return $cart;
    }
}
