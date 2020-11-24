<?php

/*
 * @copyright C UAB NFQ Technologies
 *
 * This Software is the property of NFQ Technologies
 * and is protected by copyright law – it is NOT Freeware.
 *
 * Any unauthorized use of this software without a valid license key
 * is a violation of the license agreement and will be prosecuted by
 * civil and criminal law.
 *
 * Contact UAB NFQ Technologies:
 * E-mail: info@nfq.lt
 * http://www.nfq.lt
 */

declare(strict_types=1);

namespace NFQ\SyliusOmnisendPlugin\DependencyInjection;

use NFQ\SyliusOmnisendPlugin\Model\OrderDetails;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('nfq_sylius_omnisend_plugin');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('driver')
                    ->defaultValue(SyliusResourceBundle::DRIVER_DOCTRINE_ORM)
                    ->end()
                ->end()
            ->end();
        $rootNode->children()
                ->arrayNode('resources')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('order_details')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(OrderDetails::class)->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
        $rootNode->addDefaultsIfNotSet()
                ->children()
                    ->arrayNode('order_states')
                        ->useAttributeAsKey('code')
                        ->prototype('scalar')->end()
                        ->defaultValue([])
                    ->end()
                    ->arrayNode('payment_states')
                        ->useAttributeAsKey('code')
                        ->prototype('scalar')->end()
                        ->defaultValue([])
                    ->end()
                    ->arrayNode('product_image')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('type')->defaultValue('main')->end()
                            ->scalarNode('filter')->defaultValue('sylius_shop_product_large_thumbnail')->end()
                            ->scalarNode('default_image')->defaultValue('https://placehold.it/400x30')->end()
                        ->end()
                    ->end()
                    ->arrayNode('send_welcome_message')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->booleanNode('email')->defaultValue(true)->end()
                            ->booleanNode('phone')->defaultValue(false)->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
