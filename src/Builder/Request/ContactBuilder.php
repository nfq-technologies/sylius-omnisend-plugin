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

    public function addIdentifiers(CustomerInterface $customer): void
    {
        if (null !== $customer->getEmail()) {
            $this->contact->addIdentifier(
                $this->contactIdentifierFactory->create(
                    ContactIdentifier::TYPE_EMAIL,
                    $customer->getEmail(),
                    $customer->isSubscribedToNewsletter() ? ContactIdentifierChannelValue::SUBSCRIBED : ContactIdentifierChannelValue::NON_SUBSCRIBED
                )
            );
        }

        if (null !== $customer->getPhoneNumber()) {
            $this->contact->addIdentifier(
                $this->contactIdentifierFactory->create(
                    ContactIdentifier::TYPE_PHONE,
                    $customer->getPhoneNumber(),
                    $customer->isSubscribedToSMS() ? ContactIdentifierChannelValue::SUBSCRIBED : ContactIdentifierChannelValue::NON_SUBSCRIBED
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
            ->setBirthday($customer->getBirthday() !== null ? DatetimeHelper::format($customer->getBirthday()) : null)
            ->setCreatedAt(DatetimeHelper::format($customer->getCreatedAt()));
    }

    public function addAddress(CustomerInterface $customer): void
    {
        if (null !== $customer->getDefaultAddress()) {
            $this->contact
                ->setCountryCode($customer->getDefaultAddress()->getCountryCode())
                ->setCountry($this->getCountryName($customer->getDefaultAddress()->getCountryCode()))
                ->setState($customer->getDefaultAddress()->getProvinceName())
                ->setCity($customer->getDefaultAddress()->getCity())
                ->setPostalCode($customer->getDefaultAddress()->getPostcode())
                ->setAddress($customer->getDefaultAddress()->getStreet());
        }
    }

    public function addCustomProperties(CustomerInterface $customer): void
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
        } catch (MissingResourceException $exception) {
        }

        return null;
    }
}
