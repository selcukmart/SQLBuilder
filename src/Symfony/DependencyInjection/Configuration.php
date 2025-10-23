<?php

declare(strict_types=1);

namespace SelcukMart\SQLBuilder\Symfony\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('selcuk_mart_sql_builder');

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('formatter')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('highlight')->defaultTrue()->end()
                    ->end()
                ->end()
                ->arrayNode('security')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('allow_raw_sql')->defaultTrue()->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
