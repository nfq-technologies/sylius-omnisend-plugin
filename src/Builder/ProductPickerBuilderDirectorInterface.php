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

namespace NFQ\SyliusOmnisendPlugin\Builder;

use NFQ\SyliusOmnisendPlugin\Model\ProductPicker;
use Sylius\Component\Core\Model\ProductInterface;

interface ProductPickerBuilderDirectorInterface
{
    public function build(ProductInterface $product, string $localeCode): ?ProductPicker;
}
