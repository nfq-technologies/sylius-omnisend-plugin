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

namespace Tests\NFQ\SyliusOmnisendPlugin\Factory\Request;

use NFQ\SyliusOmnisendPlugin\Factory\Request\CartProductFactory;
use NFQ\SyliusOmnisendPlugin\Resolver\ProductImageResolverInterface;
use NFQ\SyliusOmnisendPlugin\Resolver\ProductUrlResolverInterface;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\AdjustmentInterface;
use Sylius\Component\Core\Model\OrderItem;
use Sylius\Component\Core\Model\OrderItemUnit;
use Sylius\Component\Core\Model\Product;
use Sylius\Component\Core\Model\ProductTranslation;
use Sylius\Component\Core\Model\ProductVariant;
use Sylius\Component\Order\Model\Adjustment;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Tests\NFQ\SyliusOmnisendPlugin\Application\Entity\Order;

class CartProductFactoryTest extends TestCase
{
    /** @var CartProductFactory */
    private $factory;

    /** @var ProductImageResolverInterface */
    private $productImageResolver;

    /** @var ProductUrlResolverInterface */
    private $productUrlResolver;

    protected function setUp(): void
    {
        $this->productImageResolver = $this->createMock(ProductImageResolverInterface::class);
        $this->productUrlResolver = $this->createMock(ProductUrlResolverInterface::class);

        $this->factory = new CartProductFactory(
            $this->productImageResolver,
            $this->productUrlResolver
        );
    }

    public function testIfCreatesWell(): void
    {
        $order = new Order();
        $order->setLocaleCode('en');
        $orderItem = new OrderItem();
        $orderItem->setOrder($order);
        $orderItem->setUnitPrice(5000);

        $itemUnit = new OrderItemUnit($orderItem);
        $orderItem->addUnit($itemUnit);
        $orderItem->setProductName('Name');
        $adjustment1 = new Adjustment();
        $adjustment1->setAmount(-1000);
        $adjustment1->setType(AdjustmentInterface::ORDER_UNIT_PROMOTION_ADJUSTMENT);
        $itemUnit->addAdjustment($adjustment1);

        $productTranslation = new ProductTranslation();
        $productTranslation->setLocale('en');
        $productTranslation->setSlug('product');

        $product = new Product();
        $product->addTranslation($productTranslation);
        $product->setCode('product_code');

        $variant = new ProductVariant();
        $variant->setProduct($product);
        $variant->setCode('variant_code');
        $orderItem->setVariant($variant);
        $this->productUrlResolver
            ->expects($this->once())
            ->method('resolve')
            ->willReturn('url');
        $this->productImageResolver
            ->expects($this->once())
            ->method('resolve')
            ->willReturn('test.jpg');


        $product = $this->factory->create($orderItem);

        $this->assertEquals('Name', $product->getTitle());
        $this->assertEquals('url', $product->getProductUrl());
        $this->assertEquals('test.jpg', $product->getImageUrl());
        $this->assertEquals(4000, $product->getPrice());
        $this->assertEquals(5000, $product->getOldPrice());
        $this->assertEquals(1, $product->getQuantity());
        $this->assertEquals(1000, $product->getDiscount());
        $this->assertEquals('product_code', $product->getSku());
        $this->assertEquals('variant_code', $product->getVariantID());
    }
}
