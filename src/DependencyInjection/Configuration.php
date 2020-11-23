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
        //Logger
        $rootNode
            ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('client_logger')
                        ->defaultNull()
                    ->end()
                ->end()
            ->end();
        //Resolvers
        $rootNode
            ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('product_image_resolver')
                        ->defaultValue('nfq_sylius_omnisend_plugin.resolver.default_product_image')
                    ->end()
                    ->scalarNode('order_coupon_resolver')
                        ->defaultValue('nfq_sylius_omnisend_plugin.resolver.default_order_coupon')
                    ->end()
                    ->scalarNode('product_additional_data_resolver')
                        ->defaultValue('nfq_sylius_omnisend_plugin.resolver.default_product_additional_data')
                    ->end()
                    ->scalarNode('product_url_resolver')
                        ->defaultValue('nfq_sylius_omnisend_plugin.resolver.default_product_url')
                    ->end()
                    ->scalarNode('product_variant_stock_resolver')
                        ->defaultValue('nfq_sylius_omnisend_plugin.resolver.default_product_variant_stock')
                    ->end()
                ->end()
            ->end();
        //Resources
        $rootNode
            ->children()
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
        //Parameters
        $rootNode
            ->addDefaultsIfNotSet()
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
                    ->arrayNode('product_attributes')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('vendor')->defaultValue('omnisend_vendor')->end()
                            ->scalarNode('type')->defaultValue('omnisend_type')->end()
                            ->arrayNode('tags')
                                ->prototype('scalar')->end()
                                ->defaultValue(['omnisend_tag_1', 'omnisend_tag_2'])
                            ->end()
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
