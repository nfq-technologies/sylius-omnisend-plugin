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
use NFQ\SyliusOmnisendPlugin\Resolver\ProductImageResolverInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Calculator\ProductVariantPricesCalculatorInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Product\Resolver\ProductVariantResolverInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class ProductPickerBuilder implements ProductPickerBuilderInterface
{
    /**
     * @var ProductImageResolverInterface
     */
    private $productImageResolver;

    /**
     * @var ProductVariantResolverInterface
     */
    private $productVariantResolver;

    /**
     * @var ProductVariantPricesCalculatorInterface
     */
    private $productVariantPricesCalculator;

    /**
     * @var ChannelContextInterface
     */
    private $channelContext;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(
        ProductImageResolverInterface $productImageResolver,
        ProductVariantResolverInterface $productVariantResolver,
        ProductVariantPricesCalculatorInterface $productVariantPricesCalculator,
        ChannelContextInterface $channelContext,
        RouterInterface $router
    ) {
        $this->productImageResolver = $productImageResolver;
        $this->productVariantResolver = $productVariantResolver;
        $this->productVariantPricesCalculator = $productVariantPricesCalculator;
        $this->channelContext = $channelContext;
        $this->router = $router;
    }

    public function build(ProductInterface $product, string $locale): ProductPicker
    {
        $picker = new ProductPicker();
        $translation = $product->getTranslation($locale);
        /** @var ProductVariantInterface $variant */
        $variant = $this->productVariantResolver->getVariant($product);

        return $picker
            ->setProductID($product->getCode())
            ->setVariantID($variant->getCode())
            ->setTitle($translation->getName())
            ->setProductUrl(
                $this->router->generate(
                    'sylius_shop_product_show',
                    [
                        'slug' => $product->getSlug(),
                    ],
                    UrlGeneratorInterface::ABSOLUTE_URL
                )
            )
            ->setDescription($translation->getDescription())
            ->setImageUrl($this->productImageResolver->resolve($product))
            ->setPrice(
                $this->productVariantPricesCalculator->calculate(
                    $variant,
                    [
                        'channel' => $this->channelContext->getChannel(),
                    ]
                )
            )->setOldPrice(
                $this->productVariantPricesCalculator->calculate(
                    $variant,
                    [
                        'channel' => $this->channelContext->getChannel(),
                    ]
                )
            )->setCurrency($this->channelContext->getChannel()->getBaseCurrency()->getCode());
    }
}
