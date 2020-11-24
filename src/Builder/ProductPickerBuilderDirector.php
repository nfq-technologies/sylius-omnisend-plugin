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
use Sylius\Component\Core\Model\ProductVariant;
use Sylius\Component\Product\Resolver\ProductVariantResolverInterface;
use Throwable;

class ProductPickerBuilderDirector implements ProductPickerBuilderDirectorInterface
{
    /** @var ProductVariantResolverInterface */
    private $productVariantResolver;

    /** @var ProductPickerBuilderInterface */
    private $productPickerBuilder;

    public function __construct(
        ProductVariantResolverInterface $productVariantResolver,
        ProductPickerBuilderInterface $productPickerBuilder
    ) {
        $this->productVariantResolver = $productVariantResolver;
        $this->productPickerBuilder = $productPickerBuilder;
    }

    public function build(ProductInterface $product, string $locale): ?ProductPicker
    {
        /** @var ProductVariant|null $variant */
        $variant = $this->productVariantResolver->getVariant($product);

        if (null === $variant) {
            return null;
        }

        $this->productPickerBuilder->createProductPicker();

        try {
            $this->productPickerBuilder->addIds($product, $variant);
            $this->productPickerBuilder->addContent($product, $locale);
            $this->productPickerBuilder->addPrices($variant);
            $this->productPickerBuilder->addImage($product);
            $this->productPickerBuilder->addAdditionalData($product);

            return $this->productPickerBuilder->getProductPicker();
        } catch (Throwable $exception) {
            return null;
        }
    }
}
