<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            // dependencies
            new Leaphly\CartBundle\LeaphlyCartBundle(),
            new PUGX\GodfatherBundle\GodfatherBundle(),
            new Finite\Bundle\FiniteBundle\FiniteFiniteBundle(),
            // rest usage
            new FOS\RestBundle\FOSRestBundle(),
            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new Nelmio\ApiDocBundle\NelmioApiDocBundle(),
            // your domain
            new Acme\CartBundle\AcmeCartBundle(),
            new Acme\Product\TshirtBundle\AcmeTshirtBundle(),
            new Acme\Product\ConferenceBundle\AcmeConferenceBundle(),
            // website
            new Acme\SimplePurchaseProcessBundle\SimplePurchaseProcessBundle(),
            new Leaphly\ContentBundle\LeaphlyContentBundle(),
            // mongo
            new Doctrine\Bundle\MongoDBBundle\DoctrineMongoDBBundle(),
        );
        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Liip\FunctionalTestBundle\LiipFunctionalTestBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }
        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
