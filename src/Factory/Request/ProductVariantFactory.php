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

namespace NFQ\SyliusOmnisendPlugin\Factory\Request;

use NFQ\SyliusOmnisendPlugin\Client\Request\Model\ProductVariant;
use NFQ\SyliusOmnisendPlugin\Resolver\ProductAdditionalDataResolverInterface;
use NFQ\SyliusOmnisendPlugin\Resolver\ProductUrlResolverInterface;
use NFQ\SyliusOmnisendPlugin\Resolver\ProductVariantStockResolverInterface;
use Sylius\Component\Core\Calculator\ProductVariantPricesCalculatorInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Product\Model\ProductVariantTranslationInterface;

class ProductVariantFactory implements ProductVariantFactoryInterface
{
    /** @var ProductUrlResolverInterface */
    private $productUrlResolver;

    /** @var ProductVariantStockResolverInterface */
    private $productStockResolver;

    /** @var ProductVariantPricesCalculatorInterface */
    private $productVariantPricesCalculator;

    /** @var ProductAdditionalDataResolverInterface */
    private $productAdditionalDataResolver;

    public function __construct(
        ProductUrlResolverInterface $productUrlResolver,
        ProductVariantStockResolverInterface $productStockResolver,
        ProductVariantPricesCalculatorInterface $productVariantPricesCalculator,
        ProductAdditionalDataResolverInterface $productAdditionalDataResolver
    ) {
        $this->productUrlResolver = $productUrlResolver;
        $this->productStockResolver = $productStockResolver;
        $this->productVariantPricesCalculator = $productVariantPricesCalculator;
        $this->productAdditionalDataResolver = $productAdditionalDataResolver;
    }

    /** @param ProductVariantInterface $productVariant */
    public function create(
        ProductVariantInterface $productVariant,
        ChannelInterface $channel,
        string $localeCode = null
    ): ProductVariant {
        $variant = new ProductVariant();
        /** @var ProductVariantTranslationInterface $translation */
        $translation = $productVariant->getTranslation($localeCode);
        /** @var ProductInterface $product */
        $product = $productVariant->getProduct();
        $productTranslation = $product->getTranslation($localeCode);

        $price = $this->productVariantPricesCalculator->calculate(
            $productVariant,
            [
                'channel' => $channel,
            ]
        );

        $oldPrice = $this->productVariantPricesCalculator->calculateOriginal(
            $productVariant,
            [
                'channel' => $channel,
            ]
        );

        return $variant->setVariantID($productVariant->getCode())
            ->setTitle($translation->getName() ?? $productTranslation->getName())
            ->setProductUrl($this->productUrlResolver->resolve($product, $localeCode))
            ->setStatus($this->productStockResolver->resolve($productVariant))
            ->setPrice($price)
            ->setCustomFields($this->productAdditionalDataResolver->getCustomFields($product))
            ->setOldPrice($oldPrice > $price ? $oldPrice : null)
            ->setSku($productVariant->getCode());
    }
}
