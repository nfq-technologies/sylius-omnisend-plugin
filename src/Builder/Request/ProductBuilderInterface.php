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

    public function addAdditionalData(ProductInterface $product): void;

    public function addStockStatus(ProductInterface $product): void;
}
