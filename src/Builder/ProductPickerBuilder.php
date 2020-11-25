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
use NFQ\SyliusOmnisendPlugin\Resolver\ProductAdditionalDataResolverInterface;
use NFQ\SyliusOmnisendPlugin\Resolver\ProductImageResolverInterface;
use NFQ\SyliusOmnisendPlugin\Resolver\ProductUrlResolverInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Calculator\ProductVariantPricesCalculatorInterface;
use Sylius\Component\Core\Model\Channel;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductTranslationInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Currency\Model\Currency;

class ProductPickerBuilder implements ProductPickerBuilderInterface
{
    /** @var ProductPicker */
    private $productPicker;

    /** @var ProductImageResolverInterface */
    private $productImageResolver;

    /** @var ProductVariantPricesCalculatorInterface */
    private $productVariantPricesCalculator;

    /** @var ChannelContextInterface */
    private $channelContext;

    /** @var ProductUrlResolverInterface */
    private $productUrlResolver;

    /** @var ProductAdditionalDataResolverInterface */
    private $productAdditionalDataResolver;

    public function __construct(
        ProductImageResolverInterface $productImageResolver,
        ProductVariantPricesCalculatorInterface $productVariantPricesCalculator,
        ChannelContextInterface $channelContext,
        ProductUrlResolverInterface $productUrlResolver,
        ProductAdditionalDataResolverInterface $productAdditionalDataResolver
    ) {
        $this->productImageResolver = $productImageResolver;
        $this->productVariantPricesCalculator = $productVariantPricesCalculator;
        $this->channelContext = $channelContext;
        $this->productUrlResolver = $productUrlResolver;
        $this->productAdditionalDataResolver = $productAdditionalDataResolver;
    }

    public function createProductPicker(): void
    {
        $this->productPicker = new ProductPicker();
    }

    public function getProductPicker(): ProductPicker
    {
        return $this->productPicker;
    }

    public function addIds(ProductInterface $product, ProductVariantInterface $productVariant): void
    {
        $this->productPicker
            ->setProductID($product->getCode())
            ->setVariantID($productVariant->getCode());
    }

    public function addContent(ProductInterface $product, ?string $localeCode = null): void
    {
        /** @var ProductTranslationInterface $translation */
        $translation = $product->getTranslation($localeCode);

        $this->productPicker
            ->setTitle(null !== $translation->getName() ? htmlspecialchars($translation->getName()) : null)
            ->setProductUrl($this->productUrlResolver->resolve($product, $localeCode))
            ->setDescription(null !== $translation->getDescription() ? htmlspecialchars($translation->getDescription()) : null);
    }

    public function addPrices(ProductVariantInterface $productVariant): void
    {
        /** @var Channel $channel */
        $channel = $this->channelContext->getChannel();
        /** @var Currency|null $currency */
        $currency = $channel->getBaseCurrency();

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

        $this->productPicker
            ->setPrice($price)
            ->setOldPrice($oldPrice != $price ? $oldPrice : null)
            ->setCurrency($currency !== null ? $currency->getCode() : null);
    }

    public function addImage(ProductInterface $product): void
    {
        $this->productPicker->setImageUrl($this->productImageResolver->resolve($product));
    }

    public function addAdditionalData(ProductInterface $product, ?string $localeCode = null): void
    {
        $this->productPicker->setTags($this->productAdditionalDataResolver->getTags($product, $localeCode));
        $this->productPicker->setVendor($this->productAdditionalDataResolver->getVendor($product, $localeCode));
    }
}
