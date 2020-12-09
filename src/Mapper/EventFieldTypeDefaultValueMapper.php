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

namespace NFQ\SyliusOmnisendPlugin\Mapper;

class EventFieldTypeDefaultValueMapper
{
    public const TYPES = [
        'int' => 1,
        'float' => 1.01,
        'bool' => true,
        'string' => 'sylius',
        'email' => 'sylius@example.com',
        'url' => 'http://localhost.com',
        'date' => '2012-02-12',
        'dateTime' => '2017-05-30T14:11:12Z',
    ];

    public static function map(?string $type)
    {
        if (isset(self::TYPES[$type])) {
            return self::TYPES[$type];
        }

        return null;
    }
}
