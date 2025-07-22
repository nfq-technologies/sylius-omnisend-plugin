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

namespace NFQ\SyliusOmnisendPlugin\Utils;

use DateTime;

class DatetimeProvider
{
    private static ?DateTime $datetime = null;

    public static function currentDateTime(): DateTime
    {
        self::$datetime ??= new DateTime();

        return self::$datetime;
    }

    public static function setDateTime(Datetime $datetime): void
    {
        self::$datetime = $datetime;
    }
}
