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

namespace NFQ\SyliusOmnisendPlugin\Builder\Request\Constants;

class OrderFulfillmentStatus
{
    public const UNFULFILL = 'unfulfilled';
    public const PROGRESS = 'inProgress';
    public const FULFILL = 'fulfilled';
    public const DELIVER = 'delivered';
    public const RESTOCK = 'restocked';

    public const NEW = self::UNFULFILL;
}
