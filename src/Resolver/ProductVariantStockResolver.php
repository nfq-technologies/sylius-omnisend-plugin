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

namespace NFQ\SyliusOmnisendPlugin\Resolver;

use NFQ\SyliusOmnisendPlugin\Builder\Request\Constants\ProductStatus;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Inventory\Checker\AvailabilityCheckerInterface;

class ProductVariantStockResolver implements ProductVariantStockResolverInterface
{
    /** @var AvailabilityCheckerInterface */
    private $availabilityChecker;

    public function __construct(AvailabilityCheckerInterface $availabilityChecker)
    {
        $this->availabilityChecker = $availabilityChecker;
    }

    public function resolve(ProductVariantInterface $product): ?string
    {
        if (method_exists($product, 'isEnabled') && !$product->isEnabled()) {
            return ProductStatus::STATUS_NOT_AVAILABLE;
        }
        if ($this->availabilityChecker->isStockAvailable($product)) {
            return ProductStatus::STATUS_IN_STOCK;
        }

        return ProductStatus::STATUS_OUT_OF_STOCK;
    }
}
