<?php

declare(strict_types=1);

namespace Dige\TinypngPlugin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('dige_sylius_tinypng_plugin');

        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
            ->booleanNode('enabled')->defaultValue(true)->end()
            ->end()
            ->children()
            ->arrayNode('image_repositories')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
