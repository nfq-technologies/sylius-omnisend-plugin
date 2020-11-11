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

use NFQ\SyliusOmnisendPlugin\Message\Command\CreateContact;
use NFQ\SyliusOmnisendPlugin\Message\Command\UpdateContact;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Core\Model\CustomerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class CustomerSubscriber implements EventSubscriberInterface
{
    /** @var MessageBusInterface */
    private $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.customer.post_create' => 'onCreate',
            'sylius.customer.post_register' => 'onCreate',
            'sylius.customer.post_update' => 'onUpdate',
        ];
    }

    public function onCreate(ResourceControllerEvent $event)
    {
        /** @var CustomerInterface $customer */
        $customer = $event->getSubject();

        $this->messageBus->dispatch(new Envelope((new CreateContact())->setCustomerId($customer->getId())));
    }

    public function onUpdate(ResourceControllerEvent $event)
    {
        /** @var CustomerInterface $customer */
        $customer = $event->getSubject();

        $this->messageBus->dispatch(new Envelope((new UpdateContact())->setCustomerId($customer->getId())));
    }
}
