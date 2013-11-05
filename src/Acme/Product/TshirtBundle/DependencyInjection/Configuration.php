<?php

namespace Acme\Product\TshirtBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Leaphly\CartBundle\DependencyInjection\Configuration as CartConfiguration;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $nodes = $treeBuilder->root('acme_tshirt');
        $this->addDbDriver($nodes);

        return $treeBuilder;
    }

    private function addDbDriver(ArrayNodeDefinition $node)
    {
        $node
            ->children()
            ->scalarNode('db_driver')
                ->validate()
                    ->ifNotInArray(CartConfiguration::$supportedDrivers)
                    ->thenInvalid('The driver %s is not supported. Please choose one of '.json_encode(CartConfiguration::$supportedDrivers))
                ->end()
                ->cannotBeOverwritten()
                ->defaultValue('mongodb')
                ->cannotBeEmpty()
                ->end()
            ->end();
    }
}
