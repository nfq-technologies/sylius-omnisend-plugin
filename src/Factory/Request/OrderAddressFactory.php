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

namespace NFQ\SyliusOmnisendPlugin\Factory\Request;

use NFQ\SyliusOmnisendPlugin\Client\Request\Model\OrderAddress;
use Sylius\Component\Core\Model\AddressInterface;
use Symfony\Component\Intl\Countries;

class OrderAddressFactory implements OrderAddressFactoryInterface
{
    public function create(AddressInterface $address, string $locale): OrderAddress
    {
        return (new OrderAddress())
            ->setFirstName($address->getFirstName())
            ->setLastName($address->getLastName())
            ->setCompany($address->getCompany())
            ->setPhone($address->getPhoneNumber())
            ->setCountry(Countries::getName($address->getCountryCode(), $locale))
            ->setCountryCode($address->getCountryCode())
            ->setState($address->getProvinceName())
            ->setStateCode($address->getProvinceCode())
            ->setCity($address->getCity())
            ->setAddress($address->getStreet())
            ->setPostalCode($address->getPostcode());
    }
}
