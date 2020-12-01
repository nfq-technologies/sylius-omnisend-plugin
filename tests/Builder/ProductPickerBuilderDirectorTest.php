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
