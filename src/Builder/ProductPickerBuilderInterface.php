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

namespace NFQ\SyliusOmnisendPlugin\Builder;

use NFQ\SyliusOmnisendPlugin\Model\ProductPicker;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;

interface ProductPickerBuilderInterface
{
    public function createProductPicker(): void;

    public function addIds(ProductInterface $product, ProductVariantInterface $productVariant): void;

    public function addContent(ProductInterface $product, ?string $localeCode = null): void;

    public function addPrices(ProductVariantInterface $productVariant): void;

    public function addImage(ProductInterface $product): void;

    public function addAdditionalData(ProductInterface $product, ?string $localeCode = null): void;

    public function getProductPicker(): ProductPicker;
}
