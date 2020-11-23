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

use NFQ\SyliusOmnisendPlugin\Message\Command\UpdateEvent;
use NFQ\SyliusOmnisendPlugin\Model\Event;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class EventSubscriber implements EventSubscriberInterface
{
    /** @var MessageBusInterface */
    private $messageBus;

    public function __construct(
        MessageBusInterface $messageBus
    ) {
        $this->messageBus = $messageBus;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'nfq_sylius_omnisend_plugin.event.post_update' => 'onEventChange',
            'nfq_sylius_omnisend_plugin.event.post_create' => 'onEventChange',
        ];
    }

    public function onEventChange(ResourceControllerEvent $event): void
    {
        /** @var Event $trackingEvent */
        $trackingEvent = $event->getSubject();

        $this->messageBus->dispatch(
            new Envelope(
                new UpdateEvent(
                    $trackingEvent->getCode(),
                    $trackingEvent->getChannel()->getCode()
                )
            )
        );
    }
}
