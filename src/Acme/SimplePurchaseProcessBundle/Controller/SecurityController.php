<?php

namespace Acme\SimplePurchaseProcessBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Security\Core\SecurityContext;

class SecurityController extends Controller
{
    CONST DEFAULT_REDIRECT_ROUTENAME = 'simple_purchase_process_service_payment';

    /**
     * Does the login and redirect to the route name given in the querystring ?redirect='route_name',
     *  if it is empty, it redirects to the DEFAULT_REDIRECT_ROUTENAME.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();
        $redirectToRouteName = $request->query->get('redirect');

        try {
            $targetPath = $this->get('router')->generate($redirectToRouteName, array(), true);
        } catch (RouteNotFoundException $e) {
            $targetPath = $this->get('router')->generate(self::DEFAULT_REDIRECT_ROUTENAME, array(), true);
        }

        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                SecurityContext::AUTHENTICATION_ERROR
            );
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->render(
            'SimplePurchaseProcessBundle:Security:login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                'error'         => $error,
                'target_path'   => $targetPath
            )
        );
    }
}
