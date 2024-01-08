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

namespace NFQ\SyliusOmnisendPlugin\Factory\Request;

use NFQ\SyliusOmnisendPlugin\Client\Request\Model\OrderAddress;
use Sylius\Component\Core\Model\AddressInterface;
use Symfony\Component\Intl\Countries;
use Symfony\Component\Intl\Exception\MissingResourceException;

use function method_exists;

class OrderAddressFactory implements OrderAddressFactoryInterface
{
    public function create(?AddressInterface $address, ?string $localeCode = null): ?OrderAddress
    {
        if (null === $address) {
            return null;
        }

        return (new OrderAddress())
            ->setFirstName($address->getFirstName())
            ->setLastName($address->getLastName())
            ->setCompany($address->getCompany())
            ->setPhone($address->getPhoneNumber())
            ->setCountry($this->getCountryName($address, $localeCode))
            ->setCountryCode($address->getCountryCode())
            ->setState($address->getProvinceName())
            ->setStateCode($address->getProvinceCode())
            ->setCity($address->getCity())
            ->setAddress($this->getFullStreetName($address))
            ->setPostalCode($address->getPostcode());
    }

    private function getCountryName(AddressInterface $address, ?string $localeCode): ?string
    {
        try {
            if (null !== $address->getCountryCode()) {
                return Countries::getName($address->getCountryCode(), $localeCode);
            }
        } catch (MissingResourceException $exception) {
            return null;
        }

        return null;
    }

    private function getFullStreetName(AddressInterface $address): ?string
    {
        $fullStreetName = $address->getStreet();
        $houseNumber = method_exists($address, 'getHouseNumber') ? $address->getHouseNumber() : null;

        if ($houseNumber === null) {
            return $fullStreetName;
        }

        $fullStreetName .= ' ' . $houseNumber;
        $apartmentNumber = method_exists($address, 'getApartmentNumber') ? $address->getApartmentNumber() : null;

        if ($apartmentNumber === null) {
            return $fullStreetName;
        }

        $fullStreetName .= '-' . $apartmentNumber;

        return $fullStreetName;
    }
}
