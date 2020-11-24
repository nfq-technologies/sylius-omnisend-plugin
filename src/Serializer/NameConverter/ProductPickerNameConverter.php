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
