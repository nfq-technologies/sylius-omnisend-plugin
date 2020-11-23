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
            ->setTitle($translation->getName())
            ->setProductUrl($this->productUrlResolver->resolve($product))
            ->setDescription($translation->getDescription());
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
