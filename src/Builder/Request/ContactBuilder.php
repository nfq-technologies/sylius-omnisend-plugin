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

namespace NFQ\SyliusOmnisendPlugin\Builder\Request;

use NFQ\SyliusOmnisendPlugin\Client\Request\Model\Contact;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\ContactIdentifier;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\ContactIdentifierChannelValue;
use NFQ\SyliusOmnisendPlugin\Factory\Request\ContactIdentifierFactoryInterface;
use NFQ\SyliusOmnisendPlugin\Model\ContactAwareInterface;
use NFQ\SyliusOmnisendPlugin\Resolver\CustomerAdditionalDataResolverInterface;
use NFQ\SyliusOmnisendPlugin\Utils\DatetimeHelper;
use NFQ\SyliusOmnisendPlugin\Utils\GenderHelper;
use SensitiveParameter;
use Sylius\Component\Core\Model\CustomerInterface;
use Symfony\Component\Intl\Countries;
use Symfony\Component\Intl\Exception\MissingResourceException;

class ContactBuilder implements ContactBuilderInterface
{
    /** @var Contact */
    private $contact;

    /** @var ContactIdentifierFactoryInterface */
    private $contactIdentifierFactory;

    /** @var CustomerAdditionalDataResolverInterface */
    private $customerAdditionalDataResolverInterface;

    public function __construct(
        ContactIdentifierFactoryInterface $contactIdentifierFactory,
        CustomerAdditionalDataResolverInterface $customerAdditionalDataResolverInterface
    ) {
        $this->contactIdentifierFactory = $contactIdentifierFactory;
        $this->customerAdditionalDataResolverInterface = $customerAdditionalDataResolverInterface;
    }

    public function createContact(): void
    {
        $this->contact = new Contact();
    }

    public function addIdentifiers(ContactAwareInterface $customer): void
    {
        $this->addEmailIdentifier($customer);
        $this->addPhoneIdentifier($customer);
    }

    public function addEmailIdentifier(ContactAwareInterface $customer): void
    {
        if ($customer->getEmail() !== null) {
            $this->contact->addIdentifier(
                $this->contactIdentifierFactory->create(
                    ContactIdentifier::TYPE_EMAIL,
                    $customer->getEmail(),
                    self::mapToConsent($customer->getSubscribedToEmail())
                )
            );
        }
    }

    public function addPhoneIdentifier(ContactAwareInterface $customer): void
    {
        if ($customer->getPhoneNumber() !== null) {
            $this->contact->addIdentifier(
                $this->contactIdentifierFactory->create(
                    ContactIdentifier::TYPE_PHONE,
                    $customer->getPhoneNumber(),
                    self::mapToConsent($customer->getSubscribedToSms())
                )
            );
        }
    }

    public function addCustomerDetails(CustomerInterface $customer): void
    {
        $this->contact
            ->setFirstName($customer->getFirstName())
            ->setLastName($customer->getLastName())
            ->setGender(GenderHelper::resolve($customer->getGender()))
            ->setBirthday(DatetimeHelper::format($customer->getBirthday()))
            ->setCreatedAt(DatetimeHelper::format($customer->getCreatedAt()));
    }

    public function addAddress(CustomerInterface $customer): void
    {
        if ($customer->getDefaultAddress() !== null) {
            $this->contact
                ->setCountryCode($customer->getDefaultAddress()->getCountryCode())
                ->setCountry($this->getCountryName($customer->getDefaultAddress()->getCountryCode()))
                ->setState($customer->getDefaultAddress()->getProvinceName())
                ->setCity($customer->getDefaultAddress()->getCity())
                ->setPostalCode($customer->getDefaultAddress()->getPostcode())
                ->setAddress($customer->getDefaultAddress()->getStreet());
        }
    }

    public function addCustomProperties(ContactAwareInterface $customer): void
    {
        $this->contact
            ->setTags($this->customerAdditionalDataResolverInterface->getTags($customer))
            ->setCustomProperties($this->customerAdditionalDataResolverInterface->geCustomProperties($customer));
    }

    public function getContact(): Contact
    {
        return $this->contact;
    }

    private function getCountryName(?string $countryCode): ?string
    {
        if ($countryCode === null) {
            return null;
        }

        try {
            return Countries::getName($countryCode);
        } catch (MissingResourceException) {
        }

        return null;
    }

    private static function mapToConsent(?bool $subscriptionStatus): string
    {
        return match ($subscriptionStatus) {
            null => ContactIdentifierChannelValue::NON_SUBSCRIBED,
            true => ContactIdentifierChannelValue::SUBSCRIBED,
            false => ContactIdentifierChannelValue::UNSUBSCRIBED,
        };
    }
}
