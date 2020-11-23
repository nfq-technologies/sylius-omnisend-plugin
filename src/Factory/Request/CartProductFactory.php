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

use NFQ\SyliusOmnisendPlugin\Client\Request\Model\CartProduct;
use NFQ\SyliusOmnisendPlugin\Resolver\ProductImageResolverInterface;
use NFQ\SyliusOmnisendPlugin\Resolver\ProductUrlResolverInterface;
use Sylius\Component\Core\Model\AdjustmentInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;

class CartProductFactory implements CartProductFactoryInterface
{
    /** @var ProductImageResolverInterface */
    private $productImageResolver;

    /** @var ProductUrlResolverInterface */
    private $productUrlResolver;

    public function __construct(
        ProductImageResolverInterface $productImageResolver,
        ProductUrlResolverInterface $productUrlResolver
    ) {
        $this->productImageResolver = $productImageResolver;
        $this->productUrlResolver = $productUrlResolver;
    }

    public function create(OrderItemInterface $orderItem): CartProduct
    {
        $cartItem = new CartProduct();
        /** @var OrderInterface $order */
        $order = $orderItem->getOrder();
        /** @var ProductInterface $product */
        $product = $orderItem->getProduct();
        /** @var ProductVariantInterface $variant */
        $variant = $orderItem->getVariant();
        $localeCode = $order->getLocaleCode();
        $discount = $this->getDiscount($orderItem);

        $cartItem->setCartProductID((string)$orderItem->getId());
        $cartItem->setProductID((string)$product->getId());
        $cartItem->setSku((string)$product->getCode());
        $cartItem->setVariantID((string)$variant->getCode());
        $cartItem->setTitle($orderItem->getProductName());
        $cartItem->setQuantity($orderItem->getQuantity());
        $cartItem->setPrice($orderItem->getTotal());
        $cartItem->setImageUrl($this->productImageResolver->resolve($product));
        if ($discount > 0) {
            $cartItem->setDiscount($this->getDiscount($orderItem));
            $cartItem->setOldPrice($discount + $orderItem->getTotal());
        }
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
}
