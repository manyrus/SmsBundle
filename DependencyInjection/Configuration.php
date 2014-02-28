<?php

namespace Manyrus\SmsBundle\DependencyInjection;

use Manyrus\SmsBundle\Lib\ApiType;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

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
        $rootNode = $treeBuilder->root('manyrus_sms_bundle');

        $rootNode
            ->children()
                ->integerNode("min_balance")->defaultValue(100)->end()
                ->booleanNode("is_queue_mode")->defaultValue(false)->end()
                ->booleanNode("is_test_mode")->defaultValue(false)->end()
                ->scalarNode("from")->end()
                ->arrayNode('entities')
                    ->children()
                        ->scalarNode("sms")->end()
                        ->scalarNode("error")->end()
                    ->end()
                ->end()
                ->scalarNode("api_type")
                    ->validate()
                        ->ifNotInArray(ApiType::getApiTypes())
                        ->thenInvalid('Invalid sms gate driver %s')
                    ->end()
                ->end()
                ->append($this->loadEpochtaConfig())
                ->append($this->loadSmsRuConfig())
            ->end()
        ->end()
        ;


        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }

    /**
     * @return NodeDefinition
     */
    private function loadSmsRuConfig() {
        $root = new TreeBuilder();
        $node = $root->root('sms_ru');

        $node->children()
            ->arrayNode('auth')
                ->children()
                    ->scalarNode("key")->defaultValue('')->end()
                ->end()
            ->end()
        ->end();

        return $node;
    }


    private function loadEpochtaConfig() {
        $root = new TreeBuilder();
        $node = $root->root('epochta');

        $node->children()
            ->arrayNode('auth')
                ->children()
                    ->scalarNode("private_key")->defaultValue('')->end()
                    ->scalarNode("public_key")->defaultValue('')->end()
                ->end()
            ->end()
        ->end();

        return $node;
    }
}