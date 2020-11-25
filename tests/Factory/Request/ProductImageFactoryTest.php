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

use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use NFQ\SyliusOmnisendPlugin\Factory\Request\ProductImageFactory;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\ProductImage;
use Sylius\Component\Core\Model\ProductVariant;

class ProductImageFactoryTest extends TestCase
{
    /** @var ProductImageFactory */
    private $factory;

    /** @var CacheManager */
    private $cache;

    protected function setUp(): void
    {
        $this->cache = $this->createMock(CacheManager::class);
        $this->factory = new ProductImageFactory($this->cache, 'test');
    }

    public function testIfCreatesWell()
    {
        $image = new ProductImage();
        $image->setPath('test.jpg');
        $productVariant = new ProductVariant();
        $productVariant->setCode('code_1');
        $productVariant2 = new ProductVariant();
        $productVariant2->setCode('code_2');
        $image->addProductVariant($productVariant);
        $image->addProductVariant($productVariant2);
        $this->cache
            ->expects($this->once())
            ->method('generateUrl')
            ->with('test.jpg', 'test')
            ->willReturn('test.jpg');

        $result = $this->factory->create($image);

        $this->assertEquals($result->getVariantIDs(), ['code_1', 'code_2']);
    }
}
