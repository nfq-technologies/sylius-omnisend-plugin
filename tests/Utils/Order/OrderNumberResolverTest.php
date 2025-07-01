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

    public static function data()
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
