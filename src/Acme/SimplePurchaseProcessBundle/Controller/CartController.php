<?php

namespace Acme\SimplePurchaseProcessBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Acme\Product\ConferenceBundle\Form\TicketItemType;
use Acme\Product\TshirtBundle\Form\TshirtItemType;

class CartController extends Controller
{
    /**
     * Thought to have fixtures see how to install the sandbox.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function playAction()
    {
        $conference = $this->get('doctrine_mongodb')
            ->getRepository('AcmeConferenceBundle:TicketProduct')
            ->findOneBy(array(), null, $limit = 1);

        $ticketForm = $this->createForm(new TicketItemType($conference->getId()));

        $productShirt = $this->get('doctrine_mongodb')
            ->getRepository('AcmeTshirtBundle:TshirtProduct')
            ->findOneBy(array(), null, $limit = 1);

        $shirtForm = $this->createForm(new TshirtItemType($productShirt->getId()));

        return $this->render(
            'SimplePurchaseProcessBundle:Cart:play.html.twig',
            array(
                'tshirt_form' => $shirtForm->createView(),
                'tshirt_product' => $productShirt,
                'ticket_form' => $ticketForm->createView(),
                'ticket_product' => $conference)
        );
    }
}
