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

use NFQ\SyliusOmnisendPlugin\Client\Request\Model\OrderProduct;
use NFQ\SyliusOmnisendPlugin\Resolver\ProductAdditionalDataResolverInterface;
use NFQ\SyliusOmnisendPlugin\Resolver\ProductImageResolverInterface;
use NFQ\SyliusOmnisendPlugin\Resolver\ProductUrlResolverInterface;
use Sylius\Component\Core\Model\AdjustmentInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\Model\Product;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariant;
use Sylius\Component\Core\Model\TaxonInterface;

class OrderProductFactory implements OrderProductFactoryInterface
{
    /** @var ProductImageResolverInterface */
    private $productImageResolver;

    /** @var ProductUrlResolverInterface */
    private $productUrlResolver;

    /** @var ProductAdditionalDataResolverInterface */
    private $productAdditionalDataResolver;

    public function __construct(
        ProductImageResolverInterface $productImageResolver,
        ProductUrlResolverInterface $productUrlResolver,
        ProductAdditionalDataResolverInterface $productAdditionalDataResolver
    ) {
        $this->productImageResolver = $productImageResolver;
        $this->productUrlResolver = $productUrlResolver;
        $this->productAdditionalDataResolver = $productAdditionalDataResolver;
    }

    public function create(OrderItemInterface $orderItem): OrderProduct
    {
        $orderProduct = new OrderProduct();
        /** @var OrderInterface $order */
        $order = $orderItem->getOrder();
        $localeCode = $order->getLocaleCode();
        /** @var ProductVariant $variant */
        $variant = $orderItem->getVariant();
        /** @var ProductInterface $product */
        $product = $variant->getProduct();


        $orderProduct->setProductID((string) $product->getId());
        $orderProduct->setSku((string) $product->getCode());
        $orderProduct->setVariantID((string) $variant->getCode());
        $orderProduct->setVariantTitle($orderItem->getVariantName());
        $orderProduct->setTitle($orderItem->getProductName());
        $orderProduct->setQuantity($orderItem->getQuantity());
        $orderProduct->setImageUrl($this->productImageResolver->resolve($product));
        $orderProduct->setVendor($this->productAdditionalDataResolver->getVendor($product, $localeCode));
        $orderProduct->setTags($this->productAdditionalDataResolver->getTags($product, $localeCode));
        $orderProduct->setCategoryIDs($this->getCategoriesIds($orderItem));
        $orderProduct->setProductUrl($this->productUrlResolver->resolve($product, $localeCode));

        $discount = $this->getDiscount($orderItem);
        $orderProduct->setPrice($orderItem->getUnitPrice());
        $orderProduct->setDiscount($discount);

        return $orderProduct;
    }

    private function getDiscount(OrderItemInterface $orderItem): int
    {
        return $orderItem->getFullDiscountedUnitPrice() - $orderItem->getUnitPrice();
    }

    private function getCategoriesIds(OrderItemInterface $orderItem): ?array
    {
        /** @var Product $product */
        $product = $orderItem->getProduct();

        $result = array_map(
            function (TaxonInterface $productTaxon): ?string {
                return $productTaxon->getCode();
            },
            $product->getTaxons()->toArray()
        );

        return count($result) !== 0 ? $result : null;
    }
}
