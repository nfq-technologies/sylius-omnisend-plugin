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

namespace NFQ\SyliusOmnisendPlugin\Utils\Order;

class OrderNumberResolver
{
    public static function resolve(?string $orderNumber): ?int
    {
        if (null !== $orderNumber) {
            return (int)preg_replace('/\D/', '', $orderNumber);
        }

        return null;
    }
}
