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

namespace Tests\NFQ\SyliusOmnisendPlugin\Builder\Request;

use NFQ\SyliusOmnisendPlugin\Builder\Request\Constants\ProductStatus;
use NFQ\SyliusOmnisendPlugin\Builder\Request\ProductBuilder;
use NFQ\SyliusOmnisendPlugin\Builder\Request\ProductImageListBuilderInterface;
use NFQ\SyliusOmnisendPlugin\Factory\Request\ProductVariantFactoryInterface;
use NFQ\SyliusOmnisendPlugin\Resolver\ProductAdditionalDataResolverInterface;
use NFQ\SyliusOmnisendPlugin\Resolver\ProductUrlResolverInterface;
use NFQ\SyliusOmnisendPlugin\Resolver\ProductVariantStockResolverInterface;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\Channel;
use Sylius\Component\Core\Model\Product;
use Sylius\Component\Core\Model\ProductTaxon;
use Sylius\Component\Core\Model\ProductTranslation;
use Sylius\Component\Core\Model\ProductVariant;
use \NFQ\SyliusOmnisendPlugin\Client\Request\Model\ProductVariant as OmnisendProductVariant;
use Sylius\Component\Core\Model\Taxon;
use Sylius\Component\Currency\Model\Currency;

class ProductBuilderTest extends TestCase
{
    /** @var ProductBuilder */
    private $productBuilder;

    /** @var ProductVariantFactoryInterface */
    private $productVariantFactory;

    /** @var ProductImageListBuilderInterface */
    private $productImageListBuilder;

    /** @var ProductVariantStockResolverInterface */
    private $productVariantStockResolver;

    /** @var ProductUrlResolverInterface */
    private $productUrlResolver;

    /** @var ProductAdditionalDataResolverInterface */
    private $productAdditionalDataResolver;

    protected function setUp(): void
    {
        $this->productUrlResolver = $this->createMock(ProductUrlResolverInterface::class);
        $this->productVariantFactory = $this->createMock(ProductVariantFactoryInterface::class);
        $this->productVariantStockResolver = $this->createMock(ProductVariantStockResolverInterface::class);
        $this->productImageListBuilder = $this->createMock(ProductImageListBuilderInterface::class);
        $this->productAdditionalDataResolver = $this->createMock(ProductAdditionalDataResolverInterface::class);

        $this->productBuilder = new ProductBuilder(
            $this->productVariantFactory,
            $this->productImageListBuilder,
            $this->productVariantStockResolver,
            $this->productUrlResolver,
            $this->productAdditionalDataResolver
        );
    }

    public function testIfAddsEnabledVariants()
    {
        $product = new Product();
        $productVariant = new ProductVariant();
        $productVariant->setEnabled(true);
        $productVariantDisabled = new ProductVariant();
        $productVariantDisabled->setEnabled(false);
        $product->addVariant($productVariant);
        $product->addVariant($productVariantDisabled);

        $this->productVariantFactory
            ->expects($this->once())
            ->method('create')
            ->willReturn(new OmnisendProductVariant());
        $this->productBuilder->createProduct();
        $this->productBuilder->addVariants($product, new Channel());
        $result = $this->productBuilder->getProduct();
    }

    public function testIfAddsCorrectStockDataWithNoVariants()
    {
        $product = new Product();
        $this->productBuilder->createProduct();
        $this->productBuilder->addStockStatus($product);
        $this->productVariantStockResolver
            ->expects($this->never())
            ->method('resolve');

        $result = $this->productBuilder->getProduct();

        $this->assertEquals($result->getStatus(), ProductStatus::STATUS_NOT_AVAILABLE);
    }

    public function testIfAddsCorrectStockDataWithVariant()
    {
        $product = new Product();
        $productVariant = new ProductVariant();
        $productVariant->setEnabled(true);
        $product->addVariant($productVariant);
        $this->productVariantStockResolver
            ->expects($this->once())
            ->method('resolve')
            ->willReturn(ProductStatus::STATUS_OUT_OF_STOCK);
        $this->productBuilder->createProduct();
        $this->productBuilder->addStockStatus($product);

        $result = $this->productBuilder->getProduct();

        $this->assertEquals($result->getStatus(), ProductStatus::STATUS_OUT_OF_STOCK);
    }

    public function testIfAddsCorrectStockDataWithVariant2()
    {
        $product = new Product();
        $productVariant = new ProductVariant();
        $productVariant->setEnabled(true);
        $product->addVariant($productVariant);
        $this->productVariantStockResolver
            ->expects($this->once())
            ->method('resolve')
            ->willReturn(ProductStatus::STATUS_IN_STOCK);
        $this->productBuilder->createProduct();
        $this->productBuilder->addStockStatus($product);

        $result = $this->productBuilder->getProduct();

        $this->assertEquals($result->getStatus(), ProductStatus::STATUS_IN_STOCK);
    }

    public function testIfAddsContentDataWell()
    {
        $product = new Product();
        $channel = new Channel();
        $curency = new Currency();
        $curency->setCode('EUR');
        $channel->setBaseCurrency($curency);
        $product->setCreatedAt(new \DateTime('2012-02-02 12:12:12'));
        $product->setCode('code');

        $productTaxon = new Taxon();
        $productTaxon2 = new Taxon();
        $productTaxon->setCode('vienas');
        $productTaxon2->setCode('du');
        $productTaxon12 = new ProductTaxon();
        $productTaxon12->setProduct($product);
        $productTaxon12->setTaxon($productTaxon);
        $productTaxon22 = new ProductTaxon();
        $productTaxon22->setProduct($product);
        $productTaxon22->setTaxon($productTaxon2);
        $product->addProductTaxon($productTaxon12);
        $product->addProductTaxon($productTaxon22);

        $productTranslation = new ProductTranslation();
        $productTranslation->setLocale('en');
        $productTranslation->setName('Name');
        $productTranslation->setDescription('desc');
        $productTranslation->setSlug('name');
        $product->addTranslation($productTranslation);
        $product->setCurrentLocale('en');

        $this->productUrlResolver
            ->expects($this->once())
            ->method('resolve')
            ->willReturn('url');

        $this->productBuilder->createProduct();
        $this->productBuilder->addContentData($product, $channel, 'en');
        $result = $this->productBuilder->getProduct();

        $this->assertEquals($result->getProductID(), 'code');
        $this->assertEquals($result->getTitle(), 'Name');
        $this->assertEquals($result->getProductUrl(), 'url');
        $this->assertEquals($result->getCategoryIDs(), ['vienas', 'du']);
        $this->assertEquals($result->getCurrency(), 'EUR');
        $this->assertEquals($result->getCreatedAt(), '2012-02-02T12:12:12+00:00');
        $this->assertEquals($result->getUpdatedAt(), null);
    }
}
