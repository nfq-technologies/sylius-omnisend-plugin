<?php

namespace Tests\NFQ\SyliusOmnisendPlugin\Utils;

use NFQ\SyliusOmnisendPlugin\Utils\PhoneHelper;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class PhoneHelperTest extends TestCase
{
    #[DataProvider('phoneNumbersProvider')]
    public function testNormalize(string $input, string $expected): void
    {
        $this->assertEquals($expected, PhoneHelper::normalize($input));
    }

    public static function phoneNumbersProvider(): array
    {
        return [
            'number without a prefix' => ['37060000000', '+37060000000'],
            'number with plus prefix' => ['+37060000000', '+37060000000'],
            'number with multiple plus prefixes' => ['++37060000000', '+37060000000'],
            'empty string' => ['', ''],
            'only plus' => ['+', '+'],
            'only multiple pluses' => ['+++', '+'],
        ];
    }
}
