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
