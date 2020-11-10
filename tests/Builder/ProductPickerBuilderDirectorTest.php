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

use NFQ\SyliusOmnisendPlugin\Builder\ProductPickerBuilderDirector;
use NFQ\SyliusOmnisendPlugin\Builder\ProductPickerBuilderInterface;
use NFQ\SyliusOmnisendPlugin\Model\ProductPicker;
use NFQ\SyliusOmnisendPlugin\Resolver\ProductImageResolverInterface;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\Product;
use Sylius\Component\Core\Model\ProductVariant;
use Sylius\Component\Product\Resolver\ProductVariantResolverInterface;
use Exception;

class ProductPickerBuilderDirectorTest extends TestCase
{
    /**
     * @var ProductPickerBuilderDirector
     */
    private $director;

    /**
     * @var ProductVariantResolverInterface
     */
    private $productVariantResolver;

    /**
     * @var ProductPickerBuilderInterface
     */
    private $productPickerBuilder;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->director = $this->createMock(ProductImageResolverInterface::class);
        $this->productVariantResolver = $this->createMock(ProductVariantResolverInterface::class);
        $this->productPickerBuilder = $this->createMock(ProductPickerBuilderInterface::class);

        $this->director = new ProductPickerBuilderDirector(
            $this->productVariantResolver,
            $this->productPickerBuilder,
        );
    }

    public function testProductWithoutVariant()
    {
        $product = new Product();
        $this->productVariantResolver->expects($this->once())->method('getVariant')->willReturn(null);
        $this->assertNull($this->director->build($product, 'en'));
    }

    public function testIfHandlesExceptionWell()
    {
        $product = new Product();
        $this->productVariantResolver
            ->expects($this->once())
            ->method('getVariant')
            ->willReturn(new ProductVariant());
        $this->productPickerBuilder
            ->expects($this->once())
            ->method('createProductPicker');
        $this->productPickerBuilder
            ->expects($this->once())
            ->method('addIds')->willReturnCallback(
                function () {
                    throw new Exception('Invalid data provided');
                }
            );
        $this->assertNull($this->director->build($product, 'en'));
    }

    public function testIfBuilds()
    {
        $product = new Product();
        $this->productVariantResolver
            ->expects($this->once())
            ->method('getVariant')
            ->willReturn(new ProductVariant());
        $this->productPickerBuilder->expects($this->once())->method('createProductPicker');
        $this->productPickerBuilder->expects($this->once())->method('addIds');
        $this->productPickerBuilder->expects($this->once())->method('addContent');
        $this->productPickerBuilder->expects($this->once())->method('addPrices');
        $this->productPickerBuilder->expects($this->once())->method('addImage');
        $this->productPickerBuilder->expects($this->once())->method('addAdditionalData');
        $this->productPickerBuilder
            ->expects($this->once())
            ->method('getProductPicker')
            ->willReturn(new ProductPicker());

        $result = $this->director->build($product, 'en');

        $this->assertInstanceOf(ProductPicker::class, $result);
    }
}
