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

use DateTime;
use DateTimeInterface;

class DatetimeHelper
{
    public static function format(?DateTimeInterface $dateTime): ?string
    {
        if (null !== $dateTime) {
            return $dateTime->format(DateTime::ATOM);
        }

        return null;
    }
}
