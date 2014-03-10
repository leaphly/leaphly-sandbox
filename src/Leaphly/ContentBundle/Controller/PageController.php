<?php

namespace Leaphly\ContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PageController extends Controller
{
    public function homeAction()
    {
        $content = array('title'=>'Leaphly E-commerce for PHP Symfony2');

        return $this->render('LeaphlyContentBundle:Page:home.html.twig',
            array('content'=>$content));
    }

    public function installSandboxAction()
    {
        $content = array('title'=>'Install the symfony e-commerce sandbox');

        return $this->render('LeaphlyContentBundle:Sandbox:install.html.twig',
            array('content'=>$content));
    }
}
