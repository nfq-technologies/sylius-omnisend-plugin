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

namespace NFQ\SyliusOmnisendPlugin\Builder\Request;

use NFQ\SyliusOmnisendPlugin\Client\Request\Model\Contact;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\ContactIdentifier;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\ContactIdentifierChannelValue;
use NFQ\SyliusOmnisendPlugin\Factory\Request\ContactIdentifierFactoryInterface;
use NFQ\SyliusOmnisendPlugin\Model\ContactAwareInterface;
use NFQ\SyliusOmnisendPlugin\Utils\DatetimeHelper;
use NFQ\SyliusOmnisendPlugin\Utils\GenderHelper;
use Sylius\Component\Core\Model\CustomerInterface;

class ContactBuilder implements ContactBuilderInterface
{
    /** @var Contact */
    private $contact;

    /** ContactIdentifierFactoryInterface */
    private $contactIdentifierFactory;

    public function __construct(ContactIdentifierFactoryInterface $contactIdentifierFactory)
    {
        $this->contactIdentifierFactory = $contactIdentifierFactory;
    }

    public function createContact(): void
    {
        $this->contact = new Contact();
    }

    public function addIdentifiers(CustomerInterface $customer): void
    {
        if ($customer->getEmail()) {
            $this->contact->addIdentifier(
                $this->contactIdentifierFactory->create(
                    ContactIdentifier::TYPE_EMAIL,
                    $customer->getEmail(),
                    $customer->isSubscribedToNewsletter() ? ContactIdentifierChannelValue::SUBSCRIBED : ContactIdentifierChannelValue::NON_SUBSCRIBED
                )
            );
        }

        /** @var ContactAwareInterface&CustomerInterface $customer */
        if ($customer->getPhoneNumber()) {
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
            ->setBirthday($customer->getBirthday() ? DatetimeHelper::format($customer->getBirthday()) : null)
            ->setCreatedAt(DatetimeHelper::format($customer->getCreatedAt()));
    }

    public function addAddress(CustomerInterface $customer): void
    {
        if (null !== $customer->getDefaultAddress()) {
            $this->contact
                ->setCountryCode($customer->getDefaultAddress()->getCountryCode())
                ->setState($customer->getDefaultAddress()->getProvinceName())
                ->setCity($customer->getDefaultAddress()->getCity())
                ->setPostalCode($customer->getDefaultAddress()->getPostcode())
                ->setAddress($customer->getDefaultAddress()->getStreet());
        }
    }

    public function addCustomProperties(CustomerInterface $customer): void
    {
        /** @var ContactAwareInterface $customer */
        if ($customer instanceof ContactAwareInterface) {
            $this->contact
                ->setTags($customer->getOmnisendTags())
                ->setCustomProperties($customer->getOmnisendCustomProperties());
        }
    }

    public function getContact(): Contact
    {
        return $this->contact;
    }
}
