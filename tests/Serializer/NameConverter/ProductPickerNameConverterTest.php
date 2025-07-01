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

namespace Tests\NFQ\SyliusOmnisendPlugin\Serializer\NameConverter;

use NFQ\SyliusOmnisendPlugin\Serializer\NameConverter\ProductPickerNameConverter;
use PHPUnit\Framework\TestCase;

class ProductPickerNameConverterTest extends TestCase
{
    /**
     * @var ProductPickerNameConverter
     */
    private $converter;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->converter = new ProductPickerNameConverter();
    }

    /**
     * @dataProvider data
     */
    public function testIfNormalize(string $property, string $result): void
    {
        $this->assertEquals($this->converter->normalize($property), $result);
    }

    /**
     * @dataProvider data
     */
    public function testIfDenormalize(string $property, string $result): void
    {
        $this->assertEquals($this->converter->denormalize($result), $property);
    }

    public static function data(): array
    {
        return [
            'empty string' => [
                '',
                '$',
            ],
            'custom string' => [
                'aaa',
                '$aaa',
            ],
            'string with camelCase' => [
                'camelCase',
                '$camelCase',
            ],
            'string with snake_case' => [
                'snake_case',
                '$snake_case',
            ],
            'string with ID' => [
                'camelID',
                '$camelID',
            ],
        ];
    }
}
