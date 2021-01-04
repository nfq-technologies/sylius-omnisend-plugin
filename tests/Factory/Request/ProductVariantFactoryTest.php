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

use NFQ\SyliusOmnisendPlugin\Builder\Request\Constants\ProductStatus;
use NFQ\SyliusOmnisendPlugin\Factory\Request\ProductVariantFactory;
use NFQ\SyliusOmnisendPlugin\Resolver\ProductAdditionalDataResolverInterface;
use NFQ\SyliusOmnisendPlugin\Resolver\ProductOriginalPriceResolver;
use NFQ\SyliusOmnisendPlugin\Resolver\ProductUrlResolverInterface;
use NFQ\SyliusOmnisendPlugin\Resolver\ProductVariantStockResolverInterface;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Calculator\ProductVariantPriceCalculatorInterface;
use Sylius\Component\Core\Model\Channel;
use Sylius\Component\Core\Model\Product;
use Sylius\Component\Core\Model\ProductTranslation;
use Sylius\Component\Core\Model\ProductVariant;
use Sylius\Component\Product\Model\ProductVariantTranslation;

class ProductVariantFactoryTest extends TestCase
{
    /** @var ProductVariantFactory */
    private $factory;

    /** @var ProductUrlResolverInterface */
    private $productUrlResolver;

    /** @var ProductVariantStockResolverInterface */
    private $productStockResolver;

    /** @var ProductVariantPriceCalculatorInterface */
    private $productVariantPricesCalculator;

    /** @var ProductAdditionalDataResolverInterface */
    private $productAdditionalDataResolver;

    /** @var ProductOriginalPriceResolver */
    private $productOriginalPriceResolver;

    protected function setUp(): void
    {
        $this->productUrlResolver = $this->createMock(ProductUrlResolverInterface::class);
        $this->productStockResolver = $this->createMock(ProductVariantStockResolverInterface::class);
        $this->productVariantPricesCalculator = $this->createMock(ProductVariantPriceCalculatorInterface::class);
        $this->productAdditionalDataResolver = $this->createMock(ProductAdditionalDataResolverInterface::class);
        $this->productOriginalPriceResolver = $this->createMock(ProductOriginalPriceResolver::class);

        $this->factory = new ProductVariantFactory(
            $this->productUrlResolver,
            $this->productStockResolver,
            $this->productVariantPricesCalculator,
            $this->productAdditionalDataResolver,
            $this->productOriginalPriceResolver
        );
    }

    public function testIfCreatesWell()
    {
        $baseVariant = new ProductVariant();
        $baseVariant->setCode('variantCode');
        $baseVariantTranslation = new ProductVariantTranslation();
        $baseVariantTranslation->setLocale('en');
        $baseVariantTranslation->setName('Variant trans');
        $baseVariant->addTranslation($baseVariantTranslation);
        $baseVariant->setCurrentLocale('en');

        $product = new Product();
        $productTrans = new ProductTranslation();
        $productTrans->setLocale('en');
        $productTrans->setName('Product');
        $productTrans->setSlug('product');
        $baseVariant->setCurrentLocale('en');
        $product->addTranslation($productTrans);
        $baseVariant->setProduct($product);

        $this->productUrlResolver
            ->expects($this->once())
            ->method('resolve')
            ->willReturn('url');

        $this->productStockResolver
            ->expects($this->once())
            ->method('resolve')
            ->willReturn(ProductStatus::STATUS_IN_STOCK);

        $this->productVariantPricesCalculator
            ->expects($this->once())
            ->method('calculate')
            ->willReturn(10);

        if (method_exists($this->productVariantPricesCalculator, 'calculateOriginal')) {
            $this->productVariantPricesCalculator
                ->expects($this->once())
                ->method('calculateOriginal')
                ->willReturn(20);
        } else {
            $this->productOriginalPriceResolver
                ->expects($this->once())
                ->method('calculateOriginal')
                ->willReturn(20);
        }

        $result = $this->factory->create($baseVariant, new Channel(), 'en');

        $this->assertEquals('variantCode', $result->getVariantID());
        $this->assertEquals(10, $result->getPrice());
        $this->assertEquals(20, $result->getOldPrice());
        $this->assertEquals('Variant trans', $result->getTitle());
        $this->assertEquals('url', $result->getProductUrl());
        $this->assertEquals('variantCode', $result->getSku());
        $this->assertEquals(ProductStatus::STATUS_IN_STOCK, $result->getStatus());
    }

    public function testIfDoNotSetOriginalPriceIfPriceIsSameAndSetsProductName()
    {
        $baseVariant = new ProductVariant();
        $baseVariant->setCode('variantCode');

        $product = new Product();
        $productTrans = new ProductTranslation();
        $productTrans->setLocale('en');
        $productTrans->setName('Product');
        $productTrans->setSlug('product');
        $baseVariant->setCurrentLocale('en');
        $product->addTranslation($productTrans);
        $baseVariant->setProduct($product);

        $this->productUrlResolver
            ->expects($this->once())
            ->method('resolve')
            ->willReturn('url');

        $this->productStockResolver
            ->expects($this->once())
            ->method('resolve')
            ->willReturn(ProductStatus::STATUS_IN_STOCK);

        $this->productVariantPricesCalculator
            ->expects($this->once())
            ->method('calculate')
            ->willReturn(10);

        if (method_exists($this->productVariantPricesCalculator, 'calculateOriginal')) {
            $this->productVariantPricesCalculator
                ->expects($this->once())
                ->method('calculateOriginal')
                ->willReturn(10);
        } else {
            $this->productOriginalPriceResolver
                ->expects($this->once())
                ->method('calculateOriginal')
                ->willReturn(10);
        }

        $result = $this->factory->create($baseVariant, new Channel(), 'en');

        $this->assertEquals('variantCode', $result->getVariantID());
        $this->assertEquals(10, $result->getPrice());
        $this->assertEquals(null, $result->getOldPrice());
        $this->assertEquals('Product', $result->getTitle());
        $this->assertEquals('url', $result->getProductUrl());
        $this->assertEquals('variantCode', $result->getSku());
        $this->assertEquals(ProductStatus::STATUS_IN_STOCK, $result->getStatus());
    }
}
