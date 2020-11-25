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

namespace NFQ\SyliusOmnisendPlugin;

use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Sylius\Bundle\ResourceBundle\AbstractResourceBundle;
use Sylius\Bundle\ResourceBundle\ResourceBundleInterface;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;

final class NFQSyliusOmnisendPlugin extends AbstractResourceBundle
{
    use SyliusPluginTrait;

    /** @var string */
    protected $mappingFormat = ResourceBundleInterface::MAPPING_YAML;

    /**
     * {@inheritdoc}
     */
    public function getSupportedDrivers(): array
    {
        return [
            SyliusResourceBundle::DRIVER_DOCTRINE_ORM,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getModelNamespace(): ?string
    {
        return 'NFQ\SyliusOmnisendPlugin\Model';
    }
}
