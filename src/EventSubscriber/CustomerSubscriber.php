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

namespace NFQ\SyliusOmnisendPlugin\EventSubscriber;

use NFQ\SyliusOmnisendPlugin\Manager\ContactManagerInterface;
use NFQ\SyliusOmnisendPlugin\Message\Command\UpdateContact;
use NFQ\SyliusOmnisendPlugin\Setter\ContactCookieSetter;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\AddressInterface;
use Sylius\Component\Core\Model\Customer;
use Sylius\Component\Core\Model\CustomerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class CustomerSubscriber implements EventSubscriberInterface
{
    /** @var MessageBusInterface */
    private $messageBus;

    /** @var ChannelContextInterface */
    private $channelContext;

    /** @var ContactManagerInterface */
    private $contactManager;

    /** @var ContactCookieSetter */
    private $contactCookieSetter;

    public function __construct(
        MessageBusInterface $messageBus,
        ChannelContextInterface $channelContext,
        ContactManagerInterface $contactManager,
        ContactCookieSetter $contactCookieSetter
    ) {
        $this->channelContext = $channelContext;
        $this->messageBus = $messageBus;
        $this->contactManager = $contactManager;
        $this->contactCookieSetter = $contactCookieSetter;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.customer.post_create' => 'onUpdate',
            'sylius.customer.post_register' => 'onRegister',
            'sylius.customer.post_update' => 'onUpdate',
            'sylius.address.post_update' => 'onAddressUpdate',
            'sylius.address.post_create' => 'onAddressUpdate',
        ];
    }

    public function onRegister(ResourceControllerEvent $event): void
    {
        /** @var CustomerInterface $customer */
        $customer = $event->getSubject();

        $response = $this->contactManager->pushToOmnisend($customer, $this->channelContext->getChannel()->getCode());
        if (null !== $response) {
            $this->contactCookieSetter->set($response->getContactID());
        }
    }

    public function onUpdate(ResourceControllerEvent $event): void
    {
        /** @var CustomerInterface $customer */
        $customer = $event->getSubject();

        $this->messageBus->dispatch(
            new Envelope(
                new UpdateContact(
                    $customer->getId(),
                    $this->channelContext->getChannel()->getCode()
                )
            )
        );
    }

    public function onAddressUpdate(ResourceControllerEvent $event): void
    {
        /** @var AddressInterface $address */
        $address = $event->getSubject();
        /** @var Customer|null $customer */
        $customer = $address->getCustomer();

        if (
            $customer !== null &&
            $customer->getDefaultAddress() !== null &&
            $address->getId() === $customer->getDefaultAddress()->getId()
        ) {
            $this->messageBus->dispatch(
                new Envelope(
                    new UpdateContact(
                        $customer->getId(),
                        $this->channelContext->getChannel()->getCode()
                    )
                )
            );
        }
    }
}
