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

namespace NFQ\SyliusOmnisendPlugin\Resolver;

use Sylius\Component\Core\Model\ProductInterface;

interface ProductAdditionalDataResolverInterface
{
    /**
     * Array of custom Fields.
     */
    public function getCustomFields(ProductInterface $product, string $localeCode = null): ?array;

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
