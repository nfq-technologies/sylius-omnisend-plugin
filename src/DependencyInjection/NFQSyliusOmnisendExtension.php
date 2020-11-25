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

use Psr\Log\NullLogger;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\DirectoryLoader;
use Symfony\Component\DependencyInjection\Loader\GlobFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class NFQSyliusOmnisendExtension extends AbstractResourceExtension
{
    public function load(array $config, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration($this->getConfiguration([], $container), $config);
        $container->setParameter(
            'nfq_sylius_omnisend_plugin.product_image.type',
            $config['product_image']['type']
        );
        $container->setParameter(
            'nfq_sylius_omnisend_plugin.product_image.filter',
            $config['product_image']['filter']
        );
        $container->setParameter(
            'nfq_sylius_omnisend_plugin.product_image.default_image',
            $config['product_image']['default_image']
        );
        $container->setParameter(
            'nfq_sylius_omnisend_plugin.product_attributes',
            $config['product_attributes']
        );
        $container->setParameter(
            'nfq_sylius_omnisend_plugin.send_welcome_message',
            $config['send_welcome_message']
        );
        $container->setParameter(
            'nfq_sylius_omnisend_plugin.order_states',
            $config['order_states']
        );
        $container->setParameter(
            'nfq_sylius_omnisend_plugin.payment_states',
            $config['payment_states']
        );
        $container->setAlias(
            'nfq_sylius_omnisend_plugin.resolver.product_image',
            $config['product_image_resolver']
        );
        $container->setAlias(
            'nfq_sylius_omnisend_plugin.resolver.order_coupon',
            $config['order_coupon_resolver']
        );
        $container->setAlias(
            'nfq_sylius_omnisend_plugin.resolver.courier_data_resolver',
            $config['courier_data_resolver']
        );
        $container->setAlias(
            'nfq_sylius_omnisend_plugin.resolver.product_additional_data',
            $config['product_additional_data_resolver']
        );
        $container->setAlias(
            'nfq_sylius_omnisend_plugin.resolver.product_url',
            $config['product_url_resolver']
        );
        $container->setAlias(
            'nfq_sylius_omnisend_plugin.resolver.product_variant_stock',
            $config['product_variant_stock_resolver']
        );
        $container->setAlias(
            'nfq_sylius_omnisend_plugin.resolver.customer_additional_data',
            $config['customer_additional_data']
        );

        if (isset($config['client_logger']) && $config['client_logger']) {
            $container->setAlias(
                'nfq_sylius_omnisend_plugin.client.logger',
                $config['client_logger']
            );
        } else {
            $container->register('nfq_sylius_omnisend_plugin.client.logger', NullLogger::class);
        }

        $locator = new FileLocator(__DIR__ . '/../Resources/config');
        $loader = new DelegatingLoader(
            new LoaderResolver(
                [
                    new YamlFileLoader($container, $locator),
                    new GlobFileLoader($container, $locator),
                    new DirectoryLoader($container, $locator),
                ]
            )
        );
        $this->registerResources('nfq_sylius_omnisend_plugin', $config['driver'], $config['resources'], $container);
        $loader->load('services.yaml');
    }

    public function getConfiguration(array $config, ContainerBuilder $container): ConfigurationInterface
    {
        return new Configuration();
    }
}
