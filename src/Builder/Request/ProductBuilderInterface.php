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

namespace NFQ\SyliusOmnisendPlugin\Builder\Request;

use NFQ\SyliusOmnisendPlugin\Client\Request\Model\Product;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ProductInterface;

interface ProductBuilderInterface
{
    public function createProduct(): void;

    public function getProduct(): Product;

    public function addImages(ProductInterface $product): void;

    public function addVariants(ProductInterface $product, ChannelInterface $channel, ?string $localeCode): void;

    public function addContentData(ProductInterface $product, ChannelInterface $channel, ?string $localeCode): void;

    public function addAdditionalData(ProductInterface $product, ?string $localeCode): void;

    public function addStockStatus(ProductInterface $product): void;
}
