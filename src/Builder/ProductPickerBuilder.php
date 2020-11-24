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
use NFQ\SyliusOmnisendPlugin\Model\ProductPickerAdditionalDataAwareInterface;
use NFQ\SyliusOmnisendPlugin\Resolver\ProductImageResolverInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Calculator\ProductVariantPricesCalculatorInterface;
use Sylius\Component\Core\Model\Channel;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductTranslationInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Currency\Model\Currency;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

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

    /** @var RouterInterface */
    private $router;

    public function __construct(
        ProductImageResolverInterface $productImageResolver,
        ProductVariantPricesCalculatorInterface $productVariantPricesCalculator,
        ChannelContextInterface $channelContext,
        RouterInterface $router
    ) {
        $this->productImageResolver = $productImageResolver;
        $this->productVariantPricesCalculator = $productVariantPricesCalculator;
        $this->channelContext = $channelContext;
        $this->router = $router;
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

    public function addContent(ProductInterface $product, string $locale): void
    {
        /** @var ProductTranslationInterface $translation */
        $translation = $product->getTranslation($locale);

        $this->productPicker
            ->setTitle(null !== $translation->getName() ? htmlspecialchars($translation->getName()) : null)
            ->setProductUrl(
                $this->router->generate(
                    'sylius_shop_product_show',
                    [
                        'slug' => $product->getSlug(),
                        '_locale' => $locale,
                    ],
                    UrlGeneratorInterface::ABSOLUTE_URL
                )
            )
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

    public function addAdditionalData(ProductInterface $product): void
    {
        if ($product instanceof ProductPickerAdditionalDataAwareInterface) {
            $this->productPicker->setTags($product->getOmnisendTags());
            $this->productPicker->setVendor($product->getOmnisendVendor());
        }
    }
}
