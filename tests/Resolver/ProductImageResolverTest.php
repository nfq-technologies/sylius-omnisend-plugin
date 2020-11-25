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

namespace Tests\NFQ\SyliusOmnisendPlugin\Resolver;

use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use NFQ\SyliusOmnisendPlugin\Resolver\ProductImageResolver;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\Product;
use Sylius\Component\Core\Model\ProductImage;

class ProductImageResolverTest extends TestCase
{
    /** @var ProductImageResolver */
    private $resolver;

    /** @var CacheManager */
    private $cacheManager;

    protected function setUp(): void
    {
        $this->cacheManager = $this->createMock(CacheManager::class);
    }

    public function testIfReturnsNull()
    {
        $this->resolver = new ProductImageResolver($this->cacheManager, 'test', 'filter');
        $this->cacheManager->expects($this->never())->method('resolve');
        $product = new Product();
        $image = $this->resolver->resolve($product);

        $this->assertEquals($image, null);
    }

    public function testIfReturnsDefaultImage()
    {
        $this->resolver = new ProductImageResolver(
            $this->cacheManager,
            'test',
            'filter',
            'default.jpg'
        );
        $this->cacheManager->expects($this->never())->method('resolve');
        $product = new Product();
        $image = $this->resolver->resolve($product);

        $this->assertEquals($image, 'default.jpg');
    }

    public function testIfReturnsCorrectImageAndAppliesFilter()
    {
        $this->resolver = new ProductImageResolver($this->cacheManager, 'test', 'filter');
        $this->cacheManager->expects($this->exactly(2))->method('generateUrl')->willReturnCallback(
            function ($image, $filter) {
                $this->assertEquals($filter, 'filter');

                return $image;
            }
        );
        $product = new Product();
        $productImage = new ProductImage();
        $productImage->setType('other');
        $productImage->setPath('other.jpg');
        $product->addImage($productImage);
        $image = $this->resolver->resolve($product);

        $this->assertEquals($image, 'other.jpg');

        $productImage = new ProductImage();
        $productImage->setType('test');
        $productImage->setPath('correct.jpg');
        $product->addImage($productImage);
        $image = $this->resolver->resolve($product);

        $this->assertEquals($image, 'correct.jpg');
    }
}
