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

namespace NFQ\SyliusOmnisendPlugin\Message\Handler;

use NFQ\SyliusOmnisendPlugin\Manager\ContactManagerInterface;
use NFQ\SyliusOmnisendPlugin\Message\Command\CreateContact;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Repository\CustomerRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateContactHandler implements MessageHandlerInterface
{
    /** @var ContactManagerInterface */
    private $contactManager;

    /** @var CustomerRepositoryInterface */
    private $customerRepository;

    public function __construct(
        ContactManagerInterface $contactManager,
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->customerRepository = $customerRepository;
        $this->contactManager = $contactManager;
    }

    public function __invoke(CreateContact $message)
    {
        /** @var CustomerInterface|null $customer */
        $customer = $this->customerRepository->find($message->getCustomerId());

        if ($customer) {
            $this->contactManager->create($customer);
        }
    }
}
