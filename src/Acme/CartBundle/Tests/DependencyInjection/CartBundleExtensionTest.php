<?php

namespace Acme\Product\CartBundle\Tests\DependencyInjection;

use Acme\CartBundle\DependencyInjection\AcmeCartExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 *
 * @author Giulio De Donato <liuggio@gmail.com>
 * @package Leaphly\CartBundle\Tests\DependencyInjection
 */
class CartBundleExtensionTest extends \PHPUnit_Framework_TestCase
{
    /** @var ContainerBuilder */
    protected $configuration;

    public function testServicesDefinitionTest()
    {
        $this->createFullConfiguration();
        $tags = $this->configuration->findTags();
        $this->assertEquals('form.type', $tags[0]);
        $taggedServices = $this->configuration->findTaggedServiceIds($tags[0]);
        $this->assertTrue(isset($taggedServices['acme_cart.cart.form.type']));
        $this->assertEquals('leaphly_cart', $taggedServices['acme_cart.cart.form.type'][0]['alias']);
        $this->assertTrue(isset($taggedServices['acme_cart.cart.limited.form.type']));
        $this->assertEquals('leaphly_cart_limited', $taggedServices['acme_cart.cart.limited.form.type'][0]['alias']);
    }

    protected function createFullConfiguration()
    {
        $this->configuration = new ContainerBuilder();
        $loader = new AcmeCartExtension();
        $config = array();
        $loader->load(array($config), $this->configuration);
        $this->assertTrue($this->configuration instanceof ContainerBuilder);
    }

    protected function tearDown()
    {
        unset($this->configuration);
    }
}
