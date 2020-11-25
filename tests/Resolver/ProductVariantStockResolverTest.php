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

use NFQ\SyliusOmnisendPlugin\Builder\Request\Constants\ProductStatus;
use NFQ\SyliusOmnisendPlugin\Resolver\ProductVariantStockResolver;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\ProductVariant;
use Sylius\Component\Inventory\Checker\AvailabilityChecker;
use Sylius\Component\Inventory\Checker\AvailabilityCheckerInterface;

class ProductVariantStockResolverTest extends TestCase
{
    /** @var ProductVariantStockResolver */
    private $resolver;

    /** @var AvailabilityCheckerInterface */
    private $availabilityChecker;

    protected function setUp(): void
    {
        $this->availabilityChecker = new AvailabilityChecker();
        $this->resolver = new ProductVariantStockResolver($this->availabilityChecker);
    }

    public function testIfReturnNotActive()
    {
        $productVariant = new ProductVariant();
        $productVariant->setEnabled(false);

        $this->assertEquals($this->resolver->resolve($productVariant), ProductStatus::STATUS_NOT_AVAILABLE);
    }

    public function testIfReturnsInStock()
    {
        $productVariant = new ProductVariant();
        $productVariant->setEnabled(true);
        $productVariant->setOnHand(5);
        $productVariant->setTracked(true);

        $this->assertEquals($this->resolver->resolve($productVariant), ProductStatus::STATUS_IN_STOCK);
    }

    public function testIfReturnsOutStock()
    {
        $productVariant = new ProductVariant();
        $productVariant->setEnabled(true);
        $productVariant->setOnHand(0);
        $productVariant->setTracked(true);

        $this->assertEquals($this->resolver->resolve($productVariant), ProductStatus::STATUS_OUT_OF_STOCK);
    }
}
