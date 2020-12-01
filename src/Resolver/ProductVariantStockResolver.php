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
        if (!$product->isEnabled()) {
            return ProductStatus::STATUS_NOT_AVAILABLE;
        } elseif ($this->availabilityChecker->isStockAvailable($product)) {
            return ProductStatus::STATUS_IN_STOCK;
        }

        return ProductStatus::STATUS_OUT_OF_STOCK;
    }
}
