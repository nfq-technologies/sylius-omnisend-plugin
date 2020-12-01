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
