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
            ->setAddress($address->getStreet())
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
}
