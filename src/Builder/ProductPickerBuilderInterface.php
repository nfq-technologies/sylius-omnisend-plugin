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

namespace NFQ\SyliusOmnisendPlugin\Builder;

use NFQ\SyliusOmnisendPlugin\Model\ProductPicker;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;

interface ProductPickerBuilderInterface
{
    public function createProductPicker();

    public function addIds(ProductInterface $product, ProductVariantInterface $productVariant);

    public function addContent(ProductInterface $product, string $locale);

    public function addPrices(ProductVariantInterface $productVariant);

    public function addImage(ProductInterface $product);

    public function addAdditionalData(ProductInterface $product);

    public function getProductPicker(): ProductPicker;
}
