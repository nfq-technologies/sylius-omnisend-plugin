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
        $contact = $this->getCurrentContact($customer, $channelCode);

        if ($contact !== null) {
            $response = $this->omnisendClient->patchContact(
                $contact->getContactID(),
                $this->contactBuilderDirector->build($customer),
                $channelCode
            );
        } else {
            $response = $this->omnisendClient->postContact(
                $this->contactBuilderDirector->build($customer),
                $channelCode
            );
        }

        if ($response !== null) {
            $customer->setOmnisendContactId($response->getContactID());
            $this->customerRepository->add($customer);
        }

        return $response;
    }

    private function getCurrentContact(ContactAwareInterface $customer, ?string $channelCode): ?ContactSuccess
    {
        $contacts = empty($customer->getEmail()) ? null : $this->omnisendClient->getContactByEmail(
            $customer->getEmail(),
            $channelCode,
        );

        if (!$this->isEmpty($contacts)) {
            return $this->getFirstContact($contacts);
        }

        // phone number should be normalized before searching, cause omnisend prefixes all numbers with plus on creation
        $contacts = empty($customer->getPhoneNumber()) ? null : $this->omnisendClient->getContactByPhone(
            PhoneHelper::normalize($customer->getPhoneNumber()),
            $channelCode,
        );

        if (!$this->isEmpty($contacts)) {
            return $this->getFirstContact($contacts);
        }

        return null;
    }

    /**
     * @phpstan-assert-if-false ContactSuccessList $contacts
     */
    private function isEmpty(?ContactSuccessList $contacts): bool
    {
        return $contacts === null || $contacts->isEmpty();
    }

    private function getFirstContact(ContactSuccessList $contacts): ?ContactSuccess
    {
        return $contacts->getContacts()[0] ?? null;
    }
}
