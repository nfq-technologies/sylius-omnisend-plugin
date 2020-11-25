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

    public function testIfCreatesWell()
    {
        $order = new Order();
        $order->setLocaleCode('en');
        $orderItem = new OrderItem();
        $orderItem->setOrder($order);
        $orderItem->setUnitPrice(5000);
        $orderItem->addUnit(new OrderItemUnit($orderItem));
        $orderItem->setProductName('Name');
        $adjustment1 = new Adjustment();
        $adjustment1->setAmount(-1000);
        $adjustment1->setType(AdjustmentInterface::ORDER_ITEM_PROMOTION_ADJUSTMENT);
        $orderItem->addAdjustment($adjustment1);
        $variant = new ProductVariant();
        $product = new Product();
        $productTranslation = new ProductTranslation();
        $productTranslation->setLocale('en');
        $productTranslation->setSlug('product');
        $product->addTranslation($productTranslation);
        $variant->setProduct($product);
        $product->setCode('product_code');
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

        $this->assertEquals($product->getTitle(), 'Name');
        $this->assertEquals($product->getProductUrl(), 'url');
        $this->assertEquals($product->getImageUrl(), 'test.jpg');
        $this->assertEquals($product->getPrice(), 4000);
        $this->assertEquals($product->getOldPrice(), 5000);
        $this->assertEquals($product->getQuantity(), 1);
        $this->assertEquals($product->getDiscount(), 1000);
        $this->assertEquals($product->getSku(), 'product_code');
        $this->assertEquals($product->getVariantID(), 'variant_code');
    }
}
