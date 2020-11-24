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

namespace NFQ\SyliusOmnisendPlugin\Utils;

use Datetime;

class DatetimeProvider
{
    /** @var DateTime|null */
    private static $datetime;

    public static function currentDateTime(): DateTime
    {
        return self::$datetime !== null ? self::$datetime : new Datetime();
    }

    public static function setDateTime(Datetime $datetime): void
    {
        self::$datetime = $datetime;
    }
}
