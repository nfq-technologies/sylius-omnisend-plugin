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
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Repository\CustomerRepositoryInterface;

class ContactManager implements ContactManagerInterface
{
    /** @var ContactBuilderDirectorInterface */
    private $contactBuilderDirector;

    /** @var OmnisendClientInterface */
    private $omnisendClient;

    /** @var CustomerRepositoryInterface */
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

    /** @var CustomerInterface&ContactAwareInterface */
    public function pushToOmnisend(CustomerInterface $customer, ?string $channelCode): ?ContactSuccess
    {
        $contactId = $this->getCurrentContactId($customer, $channelCode);

        if (null !== $contactId) {
            /** @var ContactSuccess|null $response */
            $response = $this->omnisendClient->patchContact(
                $contactId,
                $this->contactBuilderDirector->build($customer),
                $channelCode
            );
        } else {
            /** @var ContactSuccess|null $response */
            $response = $this->omnisendClient->postContact(
                $this->contactBuilderDirector->build($customer),
                $channelCode
            );
        }

        if (null !== $response) {
            $customer->setOmnisendContactId($response->getContactID());
            $this->customerRepository->add($customer);
        }

        return $response;
    }

    /** @var CustomerInterface&ContactAwareInterface */
    private function getCurrentContactId(CustomerInterface $customer, ?string $channelCode): ?string
    {
        /** @var ContactSuccessList|null $contacts */
        $contacts = $this->omnisendClient->getContactByEmail($customer->getEmail(), $channelCode);

        if (null !== $contacts && count($contacts->getContacts()) > 0) {
            /** @var ContactSuccess $contact */
            $contact = $contacts->getContacts()[0];

            return $contact->getContactID();
        }

        return null;
    }
}
