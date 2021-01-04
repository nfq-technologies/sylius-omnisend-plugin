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

class GenderHelper
{
    /** @var string[]|array */
    private static $genders = ['m', 'f'];

    public static function resolve(?string $gender): ?string
    {
        return in_array($gender, self::$genders, true) ? $gender : null;
    }
}
