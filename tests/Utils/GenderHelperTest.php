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
