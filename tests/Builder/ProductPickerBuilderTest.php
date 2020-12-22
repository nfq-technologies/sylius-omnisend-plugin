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

namespace Tests\NFQ\SyliusOmnisendPlugin\Builder;

use NFQ\SyliusOmnisendPlugin\Builder\ProductPickerBuilder;
use NFQ\SyliusOmnisendPlugin\Model\ProductPicker;
use NFQ\SyliusOmnisendPlugin\Resolver\ProductAdditionalDataResolverInterface;
use NFQ\SyliusOmnisendPlugin\Resolver\ProductImageResolverInterface;
use NFQ\SyliusOmnisendPlugin\Resolver\ProductOriginalPriceResolver;
use NFQ\SyliusOmnisendPlugin\Resolver\ProductUrlResolverInterface;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Calculator\ProductVariantPriceCalculatorInterface;
use Sylius\Component\Core\Model\Channel;
use Sylius\Component\Core\Model\Product;
use Sylius\Component\Core\Model\ProductTranslation;
use Sylius\Component\Core\Model\ProductVariant;
use Sylius\Component\Currency\Model\Currency;

class ProductPickerBuilderTest extends TestCase
{
    /** @var ProductImageResolverInterface */
    private $productImageResolver;

    /** @var ProductVariantPriceCalculatorInterface */
    private $productVariantPricesCalculator;

    /** @var ChannelContextInterface */
    private $channelContext;

    /** @var ProductUrlResolverInterface */
    private $productUrlResolver;

    /** @var ProductAdditionalDataResolverInterface */
    private $productAdditionalDataResolver;

    /** @var ProductPickerBuilder */
    private $builder;

    /** @var ProductOriginalPriceResolver */
    private $productOriginalPriceResolver;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->productImageResolver = $this->createMock(ProductImageResolverInterface::class);
        $this->productVariantPricesCalculator = $this->createMock(ProductVariantPriceCalculatorInterface::class);
        $this->channelContext = $this->createMock(ChannelContextInterface::class);
        $this->productUrlResolver = $this->createMock(ProductUrlResolverInterface::class);
        $this->productAdditionalDataResolver = $this->createMock(ProductAdditionalDataResolverInterface::class);
        $this->productOriginalPriceResolver = $this->createMock(ProductOriginalPriceResolver::class);

        $this->builder = new ProductPickerBuilder(
            $this->productImageResolver,
            $this->productVariantPricesCalculator,
            $this->channelContext,
            $this->productUrlResolver,
            $this->productAdditionalDataResolver,
            $this->productOriginalPriceResolver
        );
    }

    public function testIfCreatesNewObject()
    {
        $this->builder->createProductPicker();
        $this->assertInstanceOf(ProductPicker::class, $this->builder->getProductPicker());
    }

    public function testIfSetsIds()
    {
        $product = new Product();
        $product->setCode('productCode');
        $productVariant = new ProductVariant();
        $productVariant->setCode('variantCode');
        $this->builder->createProductPicker();
        $this->builder->addIds($product, $productVariant);
        $result = $this->builder->getProductPicker();

        $this->assertEquals($result->getProductID(), 'productCode');
        $this->assertEquals($result->getVariantID(), 'variantCode');
    }

    public function testIfSetsContentDetails()
    {
        $product = new Product();
        $product->setCode('productCode');
        $productVariant = new ProductVariant();
        $productVariant->setCode('variantCode');
        $this->builder->createProductPicker();
        $this->builder->addIds($product, $productVariant);
        $result = $this->builder->getProductPicker();

        $this->assertEquals($result->getProductID(), 'productCode');
        $this->assertEquals($result->getVariantID(), 'variantCode');
    }

    public function testIfSetsImageUrl()
    {
        $product = new Product();
        $product->setCode('productCode');
        $productVariant = new ProductVariant();
        $productVariant->setCode('variantCode');
        $this->builder->createProductPicker();
        $this->productImageResolver
            ->expects($this->once())
            ->method('resolve')
            ->willReturn('image.jpg');
        $this->builder->addImage($product);
        $result = $this->builder->getProductPicker();

        $this->assertEquals($result->getImageUrl(), 'image.jpg');
    }

    public function testIfSetsContent()
    {
        $product = new Product();
        $productTranslation = new ProductTranslation();
        $productTranslation->setLocale('en');
        $productTranslation->setDescription('description');
        $productTranslation->setName('name');
        $productTranslation->setSlug('product');
        $product->addTranslation($productTranslation);
        $product->setCurrentLocale('en');

        $this->productUrlResolver
            ->expects($this->once())
            ->method('resolve')
            ->willReturn('url');
        $product->setCode('productCode');
        $this->builder->createProductPicker();
        $this->builder->addContent($product, 'en');
        $result = $this->builder->getProductPicker();

        $this->assertEquals($result->getTitle(), 'name');
        $this->assertEquals($result->getDescription(), 'description');
        $this->assertEquals($result->getProductUrl(), 'url');
    }

    public function testIfSetsWithoutLocale()
    {
        $product = new Product();
        $product->setCurrentLocale('en');

        $this->productUrlResolver
            ->expects($this->once())
            ->method('resolve')
            ->willReturn('url');
        $product->setCode('productCode');
        $this->builder->createProductPicker();
        $this->builder->addContent($product, 'lt');
        $result = $this->builder->getProductPicker();

        $this->assertEquals($result->getTitle(), null);
        $this->assertEquals($result->getDescription(), null);
        $this->assertEquals($result->getProductUrl(), 'url');
    }

    public function testIfSetsPrices()
    {
        $productVariant = new ProductVariant();
        $productVariant->setCode('variantCode');

        $this->channelContext
            ->expects($this->once())
            ->method('getChannel')
            ->willReturnCallback(
                function () {
                    $channel = new Channel();
                    $currency = new Currency();
                    $currency->setCode('EUR');
                    $channel->setBaseCurrency($currency);

                    return $channel;
                }
            );

        $this->productVariantPricesCalculator
            ->expects($this->once())
            ->method('calculate')
            ->willReturn(1000);

        $this->productVariantPricesCalculator
            ->expects($this->once())
            ->method('calculateOriginal')
            ->willReturn(1200);

        $this->builder->createProductPicker();
        $this->builder->addPrices($productVariant);
        $result = $this->builder->getProductPicker();

        $this->assertEquals($result->getPrice(), 1000);
        $this->assertEquals($result->getOldPrice(), 1200);
        $this->assertEquals($result->getCurrency(), 'EUR');
    }

    public function testIfNotSetOldPrice()
    {
        $productVariant = new ProductVariant();
        $productVariant->setCode('variantCode');

        $this->channelContext
            ->expects($this->once())
            ->method('getChannel')
            ->willReturnCallback(
                function () {
                    return new Channel();
                }
            );

        $this->productVariantPricesCalculator
            ->expects($this->once())
            ->method('calculate')
            ->willReturn(1000);

        $this->productVariantPricesCalculator
            ->expects($this->once())
            ->method('calculateOriginal')
            ->willReturn(1000);

        $this->builder->createProductPicker();
        $this->builder->addPrices($productVariant);
        $result = $this->builder->getProductPicker();

        $this->assertEquals($result->getPrice(), 1000);
        $this->assertEquals($result->getOldPrice(), null);
        $this->assertEquals($result->getCurrency(), null);
    }

    public function testIfSetsAdditionalData()
    {
        $product = new Product();
        $this->builder->createProductPicker();
        $this->productAdditionalDataResolver
            ->expects($this->once())
            ->method('getVendor')
            ->willReturn('vendor');
        $this->productAdditionalDataResolver
            ->expects($this->once())
            ->method('getTags')
            ->willReturn(
                [
                    'test1',
                    'test2',
                ]
            );
        $this->builder->addAdditionalData($product);
        $result = $this->builder->getProductPicker();

        $this->assertEquals(
            $result->getVendor(),
            'vendor'
        );
        $this->assertEquals(
            $result->getTags(),
            [
                'test1',
                'test2',
            ]
        );
    }
}
