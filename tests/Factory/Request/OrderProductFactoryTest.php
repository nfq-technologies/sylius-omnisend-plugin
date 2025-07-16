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

use NFQ\SyliusOmnisendPlugin\Factory\Request\OrderProductFactory;
use NFQ\SyliusOmnisendPlugin\Resolver\ProductAdditionalDataResolverInterface;
use NFQ\SyliusOmnisendPlugin\Resolver\ProductImageResolverInterface;
use NFQ\SyliusOmnisendPlugin\Resolver\ProductUrlResolverInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\AdjustmentInterface;
use Sylius\Component\Core\Model\OrderItem;
use Sylius\Component\Core\Model\OrderItemUnit;
use Sylius\Component\Core\Model\Product;
use Sylius\Component\Core\Model\ProductTaxon;
use Sylius\Component\Core\Model\ProductTranslation;
use Sylius\Component\Core\Model\ProductVariant;
use Sylius\Component\Order\Model\Adjustment;
use Sylius\Component\Taxonomy\Model\TaxonTranslation;
use Tests\NFQ\SyliusOmnisendPlugin\Application\Entity\Order;
use Tests\NFQ\SyliusOmnisendPlugin\Application\Entity\Taxon;

class OrderProductFactoryTest extends TestCase
{
    /** @var OrderProductFactory */
    private $factory;

    /** @var ProductImageResolverInterface|MockObject */
    private $productImageResolver;

    /** @var ProductUrlResolverInterface|MockObject */
    private $productUrlResolver;

    /** @var ProductAdditionalDataResolverInterface|MockObject */
    private $productAdditionalDataResolver;

    protected function setUp(): void
    {
        $this->productImageResolver = $this->createMock(ProductImageResolverInterface::class);
        $this->productUrlResolver = $this->createMock(ProductUrlResolverInterface::class);
        $this->productAdditionalDataResolver = $this->createMock(ProductAdditionalDataResolverInterface::class);

        $this->factory = new OrderProductFactory(
            $this->productImageResolver,
            $this->productUrlResolver,
            $this->productAdditionalDataResolver
        );
    }

    public function testIfCreatesWell(): void
    {
        $productTranslation = new ProductTranslation();
        $productTranslation->setLocale('en');
        $productTranslation->setSlug('product');

        $product = new Product();
        $product->addTranslation($productTranslation);
        $product->setCurrentLocale('en');
        $product->setCode('product_code');
        $product->addProductTaxon(self::createProductTaxon(self::createTaxon('code', 'TEST')));
        $product->addProductTaxon(self::createProductTaxon(self::createTaxon('code2', 'TEST')));

        $variant = new ProductVariant();
        $variant->setCurrentLocale('en');
        $variant->setProduct($product);
        $variant->setCode('variant_code');

        $order = new Order();
        $order->setLocaleCode('en');

        $adjustment = new Adjustment();
        $adjustment->setAmount(-1000);
        $adjustment->setType(AdjustmentInterface::ORDER_UNIT_PROMOTION_ADJUSTMENT);

        $orderItem = new OrderItem();
        $orderItem->setOrder($order);
        $orderItem->setUnitPrice(5000);
        $itemUnit = new OrderItemUnit($orderItem);
        $itemUnit->addAdjustment($adjustment);
        $orderItem->addUnit($itemUnit);
        $orderItem->setProductName('Name');
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
        $this->assertEquals(5000, $product->getPrice());
        $this->assertEquals(1, $product->getQuantity());
        $this->assertEquals(1000, $product->getDiscount());
        $this->assertEquals('product_code', $product->getSku());
        $this->assertEquals('variant_code', $product->getVariantID());
        $this->assertEquals(['code', 'code2'], $product->getCategoryIDs());
    }

    private static function createTaxon(string $code, string $name, string $locale = 'en'): Taxon
    {
        $taxonTranslation = new TaxonTranslation();
        $taxonTranslation->setLocale($locale);
        $taxonTranslation->setName($name);

        $taxon = new Taxon();
        $taxon->setCode($code);
        $taxon->setCurrentLocale($locale);
        $taxon->addTranslation($taxonTranslation);

        return $taxon;
    }

    private static function createProductTaxon(Taxon $taxon): ProductTaxon
    {
        $productTaxon = new ProductTaxon();
        $productTaxon->setTaxon($taxon);

        return $productTaxon;
    }
}
