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

namespace Tests\NFQ\SyliusOmnisendPlugin\Utils\Order;

use NFQ\SyliusOmnisendPlugin\Utils\Order\OrderNumberResolver;
use PHPUnit\Framework\TestCase;

class OrderNumberResolverTest extends TestCase
{
    /** @dataProvider data */
    public function testIfResolvesWell(?string $data, ?int $result)
    {
        $this->assertEquals(OrderNumberResolver::resolve($data), $result);
    }

    public function data()
    {
        return [
            'null' => [
                null,
                null
            ],
            'number' => [
                '111',
                111
            ],
            'string' => [
                'TT0033',
                33
            ]
        ];
    }
}
