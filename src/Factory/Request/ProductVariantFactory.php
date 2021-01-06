<?php

/*
 * This file is part of the NFQ package.
 *
 * (c) Nfq Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace NFQ\SyliusOmnisendPlugin\Factory\Request;

use NFQ\SyliusOmnisendPlugin\Client\Request\Model\ProductVariant;
use NFQ\SyliusOmnisendPlugin\Resolver\ProductAdditionalDataResolverInterface;
use NFQ\SyliusOmnisendPlugin\Resolver\ProductOriginalPriceResolver;
use NFQ\SyliusOmnisendPlugin\Resolver\ProductUrlResolverInterface;
use NFQ\SyliusOmnisendPlugin\Resolver\ProductVariantStockResolverInterface;
use Sylius\Component\Core\Calculator\ProductVariantPriceCalculatorInterface;
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

    /** @var ProductVariantPriceCalculatorInterface */
    private $productVariantPricesCalculator;

    /** @var ProductAdditionalDataResolverInterface */
    private $productAdditionalDataResolver;

    /** @var ProductOriginalPriceResolver */
    private $productOriginalPriceResolver;

    public function __construct(
        ProductUrlResolverInterface $productUrlResolver,
        ProductVariantStockResolverInterface $productStockResolver,
        ProductVariantPriceCalculatorInterface $productVariantPricesCalculator,
        ProductAdditionalDataResolverInterface $productAdditionalDataResolver,
        ProductOriginalPriceResolver $productOriginalPriceResolver
    ) {
        $this->productUrlResolver = $productUrlResolver;
        $this->productStockResolver = $productStockResolver;
        $this->productVariantPricesCalculator = $productVariantPricesCalculator;
        $this->productAdditionalDataResolver = $productAdditionalDataResolver;
        $this->productOriginalPriceResolver = $productOriginalPriceResolver;
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


        if (method_exists($this->productVariantPricesCalculator, 'calculateOriginal')) {
            $oldPrice = $this->productVariantPricesCalculator->calculateOriginal(
                $productVariant,
                [
                    'channel' => $channel,
                ]
            );
        } else {
            $oldPrice = $this->productOriginalPriceResolver->calculateOriginal(
                $productVariant,
                [
                    'channel' => $channel,
                ]
            );
        }

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
