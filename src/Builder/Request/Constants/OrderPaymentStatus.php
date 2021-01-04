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

namespace NFQ\SyliusOmnisendPlugin\Builder\Request\Constants;

class OrderPaymentStatus
{
    public const AWAITING = 'awaitingPayment';

    public const PARTIALLY_PAY = 'partiallyPaid';

    public const PAY = 'paid';

    public const PARTIALLY_REFUND = 'partiallyRefunded';

    public const REFUND = 'refunded';

    public const VOID = 'voided';
}
