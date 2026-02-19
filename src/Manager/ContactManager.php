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
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->contactBuilderDirector = $contactBuilderDirector;
        $this->omnisendClient = $omnisendClient;
        $this->customerRepository = $customerRepository;
    }

    public function pushToOmnisend(ContactAwareInterface $customer, ?string $channelCode): ?ContactSuccess
    {
        $updatedContact = $this->contactBuilderDirector->build($customer);

        if (!empty($customer->getOmnisendContactId())) {
            return $this->omnisendClient->patchContact(
                $customer->getOmnisendContactId(),
                $updatedContact,
                $channelCode
            );
        }

        $contactByEmail = $this->updateByEmail($customer, $channelCode);
        $contactByPhone = $this->updateByPhone($customer, $channelCode);


        if ($contactByEmail === null && $contactByPhone === null) {
            $newContact = $this->omnisendClient->postContact(
                $updatedContact,
                $channelCode
            );

            Assert::notNull($newContact, 'Failed to create contact');
            Assert::notEmpty($newContact->getContactID(), 'Contact ID cannot be empty');
            $customer->setOmnisendContactId($newContact->getContactID());
            $this->customerRepository->add($customer);

            return $newContact;
        }

        return $contactByPhone ?? $contactByEmail;
    }

    private function updateByEmail(ContactAwareInterface $customer, ?string $channelCode): ?ContactSuccess
    {
        if (empty($customer->getEmail())) {
            return null;
        }

        $contacts = $this->omnisendClient->getContactByEmail(
            $customer->getEmail(),
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

        $this->omnisendClient->patchContact(
            $contactId,
            $this->contactBuilderDirector->build($customer),
            $channelCode
        );

        $customer->setOmnisendContactId($contactId);
        $this->customerRepository->add($customer);

        return $contact;
    }
    private function updateByPhone(ContactAwareInterface $customer, ?string $channelCode): ?ContactSuccess
    {
        if (empty($customer->getPhoneNumber())) {
            return null;
        }

        $contacts = $this->omnisendClient->getContactByPhone(
            PhoneHelper::normalize($customer->getPhoneNumber()),
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

        $this->omnisendClient->patchContact(
            $contactId,
            $this->contactBuilderDirector->build($customer),
            $channelCode
        );

        $customer->setOmnisendContactId($contactId);
        $this->customerRepository->add($customer);

        return $contact;
    }

    /**
     * @phpstan-assert-if-false ContactSuccessList $contacts
     */
    private static function isEmpty(?ContactSuccessList $contacts): bool
    {
        return $contacts?->isEmpty() ?? true;
    }

    private static function getFirstContact(?ContactSuccessList $contacts): ?ContactSuccess
    {
        if (self::isEmpty($contacts)) {
            return null;
        }

        return $contacts->getContacts()[0] ?? null;
    }
}
