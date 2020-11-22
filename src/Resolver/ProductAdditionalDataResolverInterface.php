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

use Sylius\Component\Core\Model\ProductInterface;

interface ProductAdditionalDataResolverInterface
{
    /**
     * Array of product tags.
     */
    public function getTags(ProductInterface $product, string $localeCode = null): ?array;

    /**
     * Product vendor.
     */
    public function getVendor(ProductInterface $product, string $localeCode = null): ?string;

    /**
     * A categorization that a product can be tagged with, commonly used for filtering and searching.
     * For example: book, virtualGood, music. It's not product category.
     */
    public function getType(ProductInterface $product, string $localeCode = null): ?string;
}
