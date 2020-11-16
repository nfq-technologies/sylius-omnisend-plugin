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

use Nfq\Plugin\SyliusOmnisendPlugin\Request\Order\CreateProductRequest;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\CartProduct;
use NFQ\SyliusOmnisendPlugin\Resolver\ProductImageResolverInterface;
use Sylius\Component\Core\Model\AdjustmentInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class CartProductFactory
{
    /** @var string */
    private const PRODUCT_ROUTE_NAME = 'sylius_shop_product_show';

    /** @var ProductImageResolverInterface */
    private $productImageResolver;

    /** @var RouterInterface */
    private $router;

    public function __construct(ProductImageResolverInterface $productImageResolver, RouterInterface $router)
    {
        $this->productImageResolver = $productImageResolver;
        $this->router = $router;
    }

    public function create(OrderItemInterface $orderItem): CartProduct
    {
        $cartItem = new CartProduct();

        $cartItem->setCartProductId($orderItem->getId() ? (string)$orderItem->getId() : (string)$orderItem->getVariant()->getId());
        $cartItem->setProductID((string)$orderItem->getVariant()->getProduct()->getId());
        $cartItem->setSku((string)$orderItem->getVariant()->getProduct()->getCode());
        $cartItem->setVariantID((string)$orderItem->getVariant()->getCode());
        $cartItem->setTitle($orderItem->getProductName());
        $cartItem->setQuantity($orderItem->getQuantity());
        $cartItem->setPrice($orderItem->getTotal());
        $cartItem->setImageUrl($this->productImageResolver->resolve($orderItem->getProduct()));
        $cartItem->setDiscount($orderItem->getAdjustmentsTotalRecursively(AdjustmentInterface::ORDER_UNIT_PROMOTION_ADJUSTMENT)
            + $orderItem->getAdjustmentsTotalRecursively(AdjustmentInterface::ORDER_ITEM_PROMOTION_ADJUSTMENT)
            + $orderItem->getAdjustmentsTotalRecursively(AdjustmentInterface::ORDER_PROMOTION_ADJUSTMENT));
        $locale = $orderItem->getOrder()->getLocaleCode();

        $cartItem->setProductUrl(
            $this->router->generate(
                self::PRODUCT_ROUTE_NAME,
                ['slug' => $orderItem->getProduct()->getTranslation($locale)->getSlug()],
                UrlGeneratorInterface::ABSOLUTE_URL
            )
        );

        return $cartItem;
    }
}
