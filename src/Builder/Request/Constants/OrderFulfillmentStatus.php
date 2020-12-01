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
