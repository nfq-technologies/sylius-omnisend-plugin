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

namespace Tests\NFQ\SyliusOmnisendPlugin\Utils;

use NFQ\SyliusOmnisendPlugin\Utils\GenderHelper;
use PHPUnit\Framework\TestCase;

class GenderHelperTest extends TestCase
{
    /** @dataProvider data */
    public function testIfResolveWell(?string $data, ?string $result)
    {
        $this->assertEquals(GenderHelper::resolve($data), $result);
    }

    public function data(): array
    {
        return [
            'empty string' => [
                '',
                null,
            ],
            'null' => [
                null,
                null,
            ],
            'm' => [
                'm',
                'm',
            ],
            'f' => [
                'f',
                'f',
            ],
            'u' => [
                'u',
                null,
            ],
        ];
    }
}
