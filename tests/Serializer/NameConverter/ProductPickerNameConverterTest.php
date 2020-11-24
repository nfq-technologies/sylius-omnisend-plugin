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

    public function data(): array
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
