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
