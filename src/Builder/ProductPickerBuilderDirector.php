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

    public function build(ProductInterface $product, string $localeCode): ?ProductPicker
    {
        /** @var ProductVariant|null $variant */
        $variant = $this->productVariantResolver->getVariant($product);

        if (null === $variant) {
            return null;
        }

        $this->productPickerBuilder->createProductPicker();

        try {
            $this->productPickerBuilder->addIds($product, $variant);
            $this->productPickerBuilder->addContent($product, $localeCode);
            $this->productPickerBuilder->addPrices($variant);
            $this->productPickerBuilder->addImage($product);
            $this->productPickerBuilder->addAdditionalData($product, $localeCode);

            return $this->productPickerBuilder->getProductPicker();
        } catch (Throwable $exception) {
            return null;
        }
    }
}
