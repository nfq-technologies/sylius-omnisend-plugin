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

namespace NFQ\SyliusOmnisendPlugin\Message\Handler;

use NFQ\SyliusOmnisendPlugin\Manager\ContactManagerInterface;
use NFQ\SyliusOmnisendPlugin\Message\Command\UpdateContact;
use NFQ\SyliusOmnisendPlugin\Model\ContactAwareInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Repository\CustomerRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class UpdateContactHandler implements MessageHandlerInterface
{

    public function __construct(
        private readonly ContactManagerInterface $contactManager,
        /** @var CustomerRepositoryInterface<CustomerInterface> */
        private readonly CustomerRepositoryInterface $customerRepository
    ) {
    }

    public function __invoke(UpdateContact $message): void
    {
        /** @var ContactAwareInterface|null $customer */
        $customer = $this->customerRepository->find($message->getCustomerId());

        if (null !== $customer) {
            $this->contactManager->pushToOmnisend($customer, $message->getChannelCode());
        }
    }
}
