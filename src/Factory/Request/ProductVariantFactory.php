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

namespace NFQ\SyliusOmnisendPlugin\Factory\Request;

use NFQ\SyliusOmnisendPlugin\Client\Request\Model\ProductVariant;
use NFQ\SyliusOmnisendPlugin\Model\CustomTagsAwareInterface;
use NFQ\SyliusOmnisendPlugin\Resolver\ProductUrlResolverInterface;
use NFQ\SyliusOmnisendPlugin\Resolver\ProductVariantStockResolverInterface;
use Sylius\Component\Core\Calculator\ProductVariantPricesCalculatorInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;

class ProductVariantFactory implements ProductVariantFactoryInterface
{
    /** @var ProductUrlResolverInterface */
    private $productUrlResolver;

    /** @var ProductVariantStockResolverInterface */
    private $productStockResolver;

    /** @var ProductVariantPricesCalculatorInterface */
    private $productVariantPricesCalculator;

    public function __construct(
        ProductUrlResolverInterface $productUrlResolver,
        ProductVariantStockResolverInterface $productStockResolver,
        ProductVariantPricesCalculatorInterface $productVariantPricesCalculator
    ) {
        $this->productUrlResolver = $productUrlResolver;
        $this->productStockResolver = $productStockResolver;
        $this->productVariantPricesCalculator = $productVariantPricesCalculator;
    }

    /** @param ProductVariantInterface&CustomTagsAwareInterface $productVariant */
    public function create(
        ProductVariantInterface $productVariant,
        ChannelInterface $channel,
        string $localeCode = null
    ): ProductVariant {
        $variant = new ProductVariant();

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
            ->setTitle($productVariant->getName())
            ->setProductUrl($this->productUrlResolver->resolve($productVariant->getProduct(), $localeCode))
            ->setStatus($this->productStockResolver->resolve($productVariant))
            ->setPrice($price)
            ->setCustomFields($productVariant instanceof CustomTagsAwareInterface ? $productVariant->getOmnisendCustomTags() : null)
            ->setOldPrice($oldPrice)
            ->setSku($productVariant->getCode());
    }
}
