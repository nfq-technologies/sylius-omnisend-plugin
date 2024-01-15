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

namespace NFQ\SyliusOmnisendPlugin\Builder\Request;

use Doctrine\Common\Collections\ArrayCollection;
use NFQ\SyliusOmnisendPlugin\Builder\Request\Constants\ProductStatus;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\Product;
use NFQ\SyliusOmnisendPlugin\Factory\Request\ProductVariantFactoryInterface;
use NFQ\SyliusOmnisendPlugin\Resolver\ProductAdditionalDataResolverInterface;
use NFQ\SyliusOmnisendPlugin\Resolver\ProductUrlResolverInterface;
use NFQ\SyliusOmnisendPlugin\Resolver\ProductVariantStockResolverInterface;
use NFQ\SyliusOmnisendPlugin\Utils\DatetimeHelper;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariant;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Currency\Model\CurrencyInterface;

class ProductBuilder implements ProductBuilderInterface
{
    /** @var Product */
    private $product;

    /** @var ProductVariantFactoryInterface */
    private $productVariantFactory;

    /** @var ProductImageListBuilderInterface */
    private $productImageListBuilder;

    /** @var ProductVariantStockResolverInterface */
    private $productVariantStockResolver;

    /** @var ProductUrlResolverInterface */
    private $productUrlResolver;

    /** @var ProductAdditionalDataResolverInterface */
    private $productAdditionalDataResolver;

    public function __construct(
        ProductVariantFactoryInterface $productVariantFactory,
        ProductImageListBuilderInterface $productImageListBuilder,
        ProductVariantStockResolverInterface $productVariantStockResolver,
        ProductUrlResolverInterface $productUrlResolver,
        ProductAdditionalDataResolverInterface $productAdditionalDataResolver
    ) {
        $this->productVariantFactory = $productVariantFactory;
        $this->productImageListBuilder = $productImageListBuilder;
        $this->productVariantStockResolver = $productVariantStockResolver;
        $this->productUrlResolver = $productUrlResolver;
        $this->productAdditionalDataResolver = $productAdditionalDataResolver;
    }

    public function createProduct(): void
    {
        $this->product = new Product();
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function addImages(ProductInterface $product): void
    {
        $this->product->setImages($this->productImageListBuilder->build($product));
    }

    public function addVariants(ProductInterface $product, ChannelInterface $channel, ?string $localeCode = null): void
    {
        $variants = [];
        if (
            method_exists($product, 'getEnabledVariants')
            && $product->getEnabledVariants() instanceof ArrayCollection
            && $product->getEnabledVariants()->isEmpty() === false
        ) {
            $productVariants = $product->getEnabledVariants();
        } else {
            $productVariants = $product->getVariants();
        }

        /** @var ProductVariant $variant */
        foreach ($productVariants as $variant) {
            $variants[] = $this->productVariantFactory->create($variant, $channel, $localeCode);
        }

        $this->product->setVariants($variants);
    }

    public function addContentData(ProductInterface $product, ChannelInterface $channel, ?string $localeCode = null): void
    {
        /** @var CurrencyInterface|null $currency */
        $currency = $channel->getBaseCurrency();
        $this->product->setProductID($product->getCode());
        $this->product->setCurrency(null !== $currency ? $currency->getCode() : null);

        $translation = $product->getTranslation($localeCode);

        $this->product->setTitle($translation->getName());
        $this->product->setProductUrl($this->productUrlResolver->resolve($product, $localeCode));
        $this->product->setDescription($translation->getDescription());
        $this->product->setCategoryIDs(
            array_map(
                function (TaxonInterface $productTaxon): ?string {
                    return $productTaxon->getCode();
                },
                $product->getTaxons()->toArray()
            )
        );
        $this->product->setCreatedAt(DatetimeHelper::format($product->getCreatedAt()));
        $this->product->setUpdatedAt(
            null !== $product->getUpdatedAt() ? DatetimeHelper::format($product->getUpdatedAt()) : null
        );
    }

    public function addAdditionalData(ProductInterface $product, ?string $localeCode = null): void
    {
        $this->product->setTags($this->productAdditionalDataResolver->getTags($product, $localeCode));
        $this->product->setType($this->productAdditionalDataResolver->getType($product, $localeCode));
        $this->product->setVendor($this->productAdditionalDataResolver->getVendor($product, $localeCode));
    }

    public function addStockStatus(ProductInterface $product): void
    {
        $productVariants = method_exists($product, 'getEnabledVariants') ? $product->getEnabledVariants() : $product->getVariants();

        if ($productVariants->count() === 0) {
            $this->product->setStatus(ProductStatus::STATUS_NOT_AVAILABLE);

            return;
        }

        /** @var ProductVariantInterface $variant */
        foreach ($productVariants as $variant) {
            $status = $this->productVariantStockResolver->resolve($variant);

            if ($status === ProductStatus::STATUS_IN_STOCK) {
                $this->product->setStatus(ProductStatus::STATUS_IN_STOCK);

                return;
            }
        }

        $this->product->setStatus(ProductStatus::STATUS_OUT_OF_STOCK);
    }
}
