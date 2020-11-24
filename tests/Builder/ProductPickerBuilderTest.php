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

namespace Tests\NFQ\SyliusOmnisendPlugin\Builder;

use NFQ\SyliusOmnisendPlugin\Builder\ProductPickerBuilder;
use NFQ\SyliusOmnisendPlugin\Model\ProductPicker;
use NFQ\SyliusOmnisendPlugin\Resolver\ProductImageResolverInterface;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Calculator\ProductVariantPricesCalculatorInterface;
use Sylius\Component\Core\Model\Channel;
use Sylius\Component\Core\Model\Product;
use Sylius\Component\Core\Model\ProductTranslation;
use Sylius\Component\Core\Model\ProductVariant;
use Sylius\Component\Currency\Model\Currency;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Tests\NFQ\SyliusOmnisendPlugin\Mock\ProductPickerAdditionalDataAwareMock;

class ProductPickerBuilderTest extends TestCase
{
    /**
     * @var ProductImageResolverInterface
     */
    private $productImageResolver;

    /**
     * @var ProductVariantPricesCalculatorInterface
     */
    private $productVariantPricesCalculator;

    /**
     * @var ChannelContextInterface
     */
    private $channelContext;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var ProductPickerBuilder
     */
    private $builder;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->productImageResolver = $this->createMock(ProductImageResolverInterface::class);
        $this->productVariantPricesCalculator = $this->createMock(ProductVariantPricesCalculatorInterface::class);
        $this->channelContext = $this->createMock(ChannelContextInterface::class);
        $this->router = $this->createMock(RouterInterface::class);

        $this->builder = new ProductPickerBuilder(
            $this->productImageResolver,
            $this->productVariantPricesCalculator,
            $this->channelContext,
            $this->router
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

        $this->router
            ->expects($this->once())
            ->method('generate')
            ->willReturnCallback(
                function ($route, $config, $type) {
                    $this->assertEquals($route, 'sylius_shop_product_show');
                    $this->assertEquals($config, [
                        'slug' => 'product',
                        '_locale' => 'en',
                    ]);
                    $this->assertEquals($type, UrlGeneratorInterface::ABSOLUTE_URL);

                    return 'url';
                }
            );
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

        $this->router
            ->expects($this->once())
            ->method('generate')
            ->willReturnCallback(
                function ($route, $config, $type) {
                    $this->assertEquals($route, 'sylius_shop_product_show');
                    $this->assertEquals($config, [
                        'slug' => null,
                        '_locale' => 'lt',
                    ]);
                    $this->assertEquals($type, UrlGeneratorInterface::ABSOLUTE_URL);

                    return 'url';
                }
            );
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
        $product = new ProductPickerAdditionalDataAwareMock();
        $this->builder->createProductPicker();
        $this->builder->addAdditionalData($product);
        $result = $this->builder->getProductPicker();

        $this->assertEquals($result->getVendor(), 'vendor');
        $this->assertEquals(
            $result->getTags(),
            [
                'test1',
                'test2',
            ]
        );
    }
}
