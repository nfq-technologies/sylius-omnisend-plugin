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

namespace NFQ\SyliusOmnisendPlugin\Manager;

use NFQ\SyliusOmnisendPlugin\Builder\Request\ContactBuilderDirectorInterface;
use NFQ\SyliusOmnisendPlugin\Client\OmnisendClientInterface;
use NFQ\SyliusOmnisendPlugin\Client\Response\Model\ContactSuccess;
use NFQ\SyliusOmnisendPlugin\Client\Response\Model\ContactSuccessList;
use NFQ\SyliusOmnisendPlugin\Model\ContactAwareInterface;
use NFQ\SyliusOmnisendPlugin\Utils\PhoneHelper;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Repository\CustomerRepositoryInterface;
use Webmozart\Assert\Assert;

class ContactManager implements ContactManagerInterface
{
    /** @var ContactBuilderDirectorInterface */
    private $contactBuilderDirector;

    /** @var OmnisendClientInterface */
    private $omnisendClient;

    /** @var CustomerRepositoryInterface<CustomerInterface> */
    private $customerRepository;

    public function __construct(
        ContactBuilderDirectorInterface $contactBuilderDirector,
        OmnisendClientInterface $omnisendClient,
        CustomerRepositoryInterface $customerRepository,
    ) {
        $this->contactBuilderDirector = $contactBuilderDirector;
        $this->omnisendClient = $omnisendClient;
        $this->customerRepository = $customerRepository;
    }

    public function pushToOmnisend(ContactAwareInterface $customer, ?string $channelCode): ?ContactSuccess
    {
        // don't update contact info to avoid collisions between contact identifiers
        $contactByEmail = $this->trySyncByEmail($customer, $channelCode);
        $contactByPhone = $this->trySyncByPhone($customer, $channelCode);

        $emailFound = $contactByEmail !== null;
        $phoneFound = $contactByPhone !== null;
        $hasEmail = !empty($customer->getEmail());
        $hasPhone = !empty($customer->getPhoneNumber());

        Assert::true($hasEmail || $hasPhone, 'Customer must have either email or phone number');

        if (!$emailFound && !$phoneFound) {
            return $this->createFullContact($customer, $channelCode);
        }

        if ($emailFound && !$phoneFound && $hasPhone) {
            $contactByPhone = $this->createContactWithPhoneOnly($customer, $channelCode);
        }

        if ($phoneFound && !$emailFound && $hasEmail) {
            $contactByEmail = $this->createContactWithEmailOnly($customer, $channelCode);
        }

        return $contactByPhone ?? $contactByEmail;
    }

    private function trySyncByEmail(ContactAwareInterface $customer, ?string $channelCode): ?ContactSuccess
    {
        $email = $customer->getEmail();
        if (empty($email)) {
            return null;
        }

        $contacts = $this->omnisendClient->getContactByEmail(
            $email,
            $channelCode,
        );

        $contact = self::getFirstContact($contacts);

        if ($contact === null) {
            return null;
        }

        $contactId = $contact->getContactID();
        if ($contactId === null) {
            return null;
        }

        // Only update consent using the existing email from contact, not customer's email
        $this->omnisendClient->patchContact(
            $contactId,
            $this->contactBuilderDirector->buildWithoutPhone($customer),
            $channelCode,
        );

        return $contact;
    }

    private static function getFirstContact(?ContactSuccessList $contacts): ?ContactSuccess
    {
        if (self::isEmpty($contacts)) {
            return null;
        }

        return $contacts->getContacts()[0] ?? null;
    }

    /**
     * @phpstan-assert-if-false ContactSuccessList $contacts
     * @phpstan-assert-if-true null $contacts
     */
    private static function isEmpty(?ContactSuccessList $contacts): bool
    {
        return $contacts?->isEmpty() ?? true;
    }

    private function trySyncByPhone(ContactAwareInterface $customer, ?string $channelCode): ?ContactSuccess
    {
        $phone = $customer->getPhoneNumber();
        if (empty($phone)) {
            return null;
        }

        $normalizedPhone = PhoneHelper::normalize($phone);
        $contacts = $this->omnisendClient->getContactByPhone(
            $normalizedPhone,
            $channelCode,
        );

        $contact = self::getFirstContact($contacts);

        if ($contact === null) {
            return null;
        }

        $contactId = $contact->getContactID();
        if ($contactId === null) {
            return null;
        }

        // Only update consent using the existing phone from contact, not customer's phone
        $this->omnisendClient->patchContact(
            $contactId,
            $this->contactBuilderDirector->buildWithoutEmail($customer),
            $channelCode,
        );

        return $contact;
    }

    private function createFullContact(ContactAwareInterface $customer, ?string $channelCode): ContactSuccess
    {
        $newContact = $this->omnisendClient->postContact(
            $this->contactBuilderDirector->build($customer),
            $channelCode,
        );

        Assert::notNull($newContact, 'Failed to create contact');
        Assert::notEmpty($newContact->getContactID(), 'Contact ID cannot be empty');

        $customer->setOmnisendContactId($newContact->getContactID());
        $this->customerRepository->add($customer);

        return $newContact;
    }

    private function createContactWithPhoneOnly(ContactAwareInterface $customer, ?string $channelCode): ContactSuccess
    {
        $newContact = $this->omnisendClient->postContact(
            $this->contactBuilderDirector->buildWithoutEmail($customer),
            $channelCode,
        );

        Assert::notNull($newContact, 'Failed to create contact with phone');
        Assert::notEmpty($newContact->getContactID(), 'Contact ID cannot be empty');

        return $newContact;
    }

    private function createContactWithEmailOnly(ContactAwareInterface $customer, ?string $channelCode): ContactSuccess
    {
        $newContact = $this->omnisendClient->postContact(
            $this->contactBuilderDirector->buildWithoutPhone($customer),
            $channelCode,
        );

        Assert::notNull($newContact, 'Failed to create contact with email');
        Assert::notEmpty($newContact->getContactID(), 'Contact ID cannot be empty');

        return $newContact;
    }
}
