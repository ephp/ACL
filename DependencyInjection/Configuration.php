<?php

namespace Ephp\ACLBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface {

    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder() {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ephp_acl');

        $rootNode
                ->children()
                    ->scalarNode('user_class')->defaultValue('Ephp\ACLBundle\Entity\User')->cannotBeEmpty()->end()
                    ->arrayNode('facebook')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('app_id')->defaultValue('no')->cannotBeEmpty()->end()
                            ->scalarNode('app_secret')->defaultValue('no')->cannotBeEmpty()->end()
                            ->scalarNode('app_name')->defaultValue('My Facebook App')->cannotBeEmpty()->end()
                            ->scalarNode('app_url')->defaultValue('http://www.ephp.it')->cannotBeEmpty()->end()
                            ->scalarNode('app_description')->defaultValue('This is my Facebook App on Symfony 2')->cannotBeEmpty()->end()
                        ->end()
                    ->end()
                ->end()
        ;
        return $treeBuilder;
    }

}
