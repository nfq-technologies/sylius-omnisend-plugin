<?php

namespace NFQ\SyliusOmnisendPlugin\Utils;

class PhoneHelper
{
    public static function normalize(string $number): string
    {
        if (empty($number)) {
            return '';
        }
        return sprintf('+%s', ltrim($number, '+'));
    }
}