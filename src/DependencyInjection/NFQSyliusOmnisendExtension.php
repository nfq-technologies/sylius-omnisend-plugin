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

use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
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
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $this->registerResources('nfq_sylius_omnisend_plugin', $config['driver'], $config['resources'], $container);
        $loader->load('services.yaml');
    }

    public function getConfiguration(array $config, ContainerBuilder $container): ConfigurationInterface
    {
        return new Configuration();
    }
}
