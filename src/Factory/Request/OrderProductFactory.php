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
        $cartItem = new OrderProduct();
        /** @var OrderInterface $order */
        $order = $orderItem->getOrder();
        $localeCode = $order->getLocaleCode();
        /** @var ProductVariant $variant */
        $variant = $orderItem->getVariant();
        /** @var ProductInterface $product */
        $product = $variant->getProduct();

        $cartItem->setProductID((string) $product->getId());
        $cartItem->setSku((string) $product->getCode());
        $cartItem->setVariantID((string) $variant->getCode());
        $cartItem->setVariantTitle($orderItem->getVariantName());
        $cartItem->setTitle($orderItem->getProductName());
        $cartItem->setQuantity($orderItem->getQuantity());
        $cartItem->setPrice($orderItem->getTotal());
        $cartItem->setImageUrl($this->productImageResolver->resolve($product));
        $cartItem->setDiscount($this->getDiscount($orderItem));
        $cartItem->setVendor($this->productAdditionalDataResolver->getVendor($product, $localeCode));
        $cartItem->setTags($this->productAdditionalDataResolver->getTags($product, $localeCode));
        $cartItem->setCategoryIDs($this->getCategoriesIds($orderItem));
        $cartItem->setProductUrl($this->productUrlResolver->resolve($product, $localeCode));

        return $cartItem;
    }

    private function getDiscount(OrderItemInterface $orderItem): int
    {
        return abs(
            $orderItem->getAdjustmentsTotalRecursively(AdjustmentInterface::ORDER_UNIT_PROMOTION_ADJUSTMENT)
            + $orderItem->getAdjustmentsTotalRecursively(AdjustmentInterface::ORDER_ITEM_PROMOTION_ADJUSTMENT)
            + $orderItem->getAdjustmentsTotalRecursively(AdjustmentInterface::ORDER_PROMOTION_ADJUSTMENT)
        );
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
