<?php

/*
 * This file is part of the NFQ package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace NFQ\SyliusOmnisendPlugin\DependencyInjection;

use NFQ\SyliusOmnisendPlugin\Form\Type\EventFieldType;
use NFQ\SyliusOmnisendPlugin\Form\Type\EventType;
use NFQ\SyliusOmnisendPlugin\Model\Event;
use NFQ\SyliusOmnisendPlugin\Model\EventField;
use NFQ\SyliusOmnisendPlugin\Model\OrderDetails;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Sylius\Component\Resource\Factory\Factory;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('nfq_sylius_omnisend');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('driver')
                        ->defaultValue(SyliusResourceBundle::DRIVER_DOCTRINE_ORM)
                    ->end()
                    ->scalarNode('client_logger')
                        ->defaultNull()
                    ->end()
                    ->scalarNode('product_image_resolver')
                        ->defaultValue('nfq_sylius_omnisend_plugin.resolver.default_product_image')
                    ->end()
                    ->scalarNode('order_coupon_resolver')
                        ->defaultValue('nfq_sylius_omnisend_plugin.resolver.default_order_coupon')
                    ->end()
                    ->scalarNode('courier_data_resolver')
                        ->defaultValue('nfq_sylius_omnisend_plugin.resolver.default_courier_data_resolver')
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
                    ->scalarNode('customer_additional_data')
                        ->defaultValue('nfq_sylius_omnisend_plugin.resolver.default_customer_additional_data')
                    ->end()
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
                            ->scalarNode('tags')->defaultValue('omnisend_tags')->end()
                            ->arrayNode('custom_fields')
                                ->prototype('scalar')->end()
                                ->defaultValue(['omnisend_custom_field_1', 'omnisend_custom_field_2'])
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
                        ->arrayNode('event')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')
                                            ->defaultValue(Event::class)->cannotBeEmpty()
                                        ->end()
                                        ->scalarNode('form')
                                            ->defaultValue(EventType::class)
                                        ->end()
                                        ->scalarNode('controller')
                                            ->defaultValue(ResourceController::class)
                                        ->end()
                                        ->scalarNode('factory')
                                            ->defaultValue(Factory::class)
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('event_field')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(EventField::class)->cannotBeEmpty()->end()
                                        ->scalarNode('form')
                                            ->defaultValue(EventFieldType::class)
                                        ->end()
                                        ->scalarNode('factory')
                                            ->defaultValue(Factory::class)
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
