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

namespace NFQ\SyliusOmnisendPlugin\Serializer\NameConverter;

use Symfony\Component\Serializer\NameConverter\NameConverterInterface;

class ProductPickerNameConverter implements NameConverterInterface
{
    private const PREFIX = '$';

    /**
     * {@inheritdoc}
     */
    public function normalize($propertyName)
    {
        return self::PREFIX . $propertyName;
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($propertyName)
    {
        $propertyName = self::PREFIX === substr($propertyName, 0, strlen(self::PREFIX)) ?
            substr($propertyName, strlen(self::PREFIX)) :
            $propertyName;

        return $propertyName;
    }
}
