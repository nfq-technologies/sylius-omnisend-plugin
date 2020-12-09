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

    /** @var ProductImageResolverInterface */
    private $productImageResolver;

    /** @var ProductUrlResolverInterface */
    private $productUrlResolver;

    /** @var ProductAdditionalDataResolverInterface */
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
        $product->setCurrentLocale('en');
        $variant->setCurrentLocale('en');
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
        $taxon = new Taxon();
        $taxonTranslation = new TaxonTranslation();
        $taxonTranslation->setLocale('en');
        $taxonTranslation->setName('TEST');
        $taxon->setCode('code');
        $taxon->setCurrentLocale('en');
        $taxon->addTranslation($taxonTranslation);
        $productTaxon = new ProductTaxon();
        $productTaxon->setTaxon($taxon);
        $productTaxon->setProduct($product);
        $product->addProductTaxon($productTaxon);
        $taxon1 = new Taxon();
        $taxonTranslation1 = new TaxonTranslation();
        $taxonTranslation1->setLocale('en');
        $taxonTranslation1->setName('TEST');
        $taxon1->setCode('code2');
        $taxon1->setCurrentLocale('en');
        $taxon1->addTranslation($taxonTranslation1);
        $productTaxon1 = new ProductTaxon();
        $productTaxon1->setTaxon($taxon1);
        $productTaxon1->setProduct($product);
        $product->addProductTaxon($productTaxon1);

        $product = $this->factory->create($orderItem);

        $this->assertEquals($product->getTitle(), 'Name');
        $this->assertEquals($product->getProductUrl(), 'url');
        $this->assertEquals($product->getImageUrl(), 'test.jpg');
        $this->assertEquals($product->getPrice(), 4000);
        $this->assertEquals($product->getQuantity(), 1);
        $this->assertEquals($product->getDiscount(), 1000);
        $this->assertEquals($product->getSku(), 'product_code');
        $this->assertEquals($product->getVariantID(), 'variant_code');
        $this->assertEquals($product->getCategoryIDs(), ['code', 'code2']);
    }
}
