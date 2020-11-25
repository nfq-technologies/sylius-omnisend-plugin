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

namespace NFQ\SyliusOmnisendPlugin\Factory\Request;

use NFQ\SyliusOmnisendPlugin\Client\Request\Model\ProductImage;
use Sylius\Component\Core\Model\ProductImageInterface;

interface ProductImageFactoryInterface
{
    public function create(ProductImageInterface $productImage, bool $default = false): ProductImage;
}
