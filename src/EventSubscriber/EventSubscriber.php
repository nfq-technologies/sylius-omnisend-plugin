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

namespace NFQ\SyliusOmnisendPlugin\EventSubscriber;

use NFQ\SyliusOmnisendPlugin\Event\CustomEvent;
use NFQ\SyliusOmnisendPlugin\Message\Command\PushCustomEvent;
use NFQ\SyliusOmnisendPlugin\Message\Command\UpdateEvent;
use NFQ\SyliusOmnisendPlugin\Model\Event;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Core\Model\ChannelInterface;
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
            CustomEvent::class => 'onCustomEventCreate',
        ];
    }

    public function onEventChange(ResourceControllerEvent $event): void
    {
        /** @var Event $trackingEvent */
        $trackingEvent = $event->getSubject();
        /** @var ChannelInterface $channel */
        $channel = $trackingEvent->getChannel();

        $this->messageBus->dispatch(
            new Envelope(
                new UpdateEvent(
                    $trackingEvent->getSystemName(),
                    $channel->getCode()
                )
            )
        );
    }

    public function onCustomEventCreate(CustomEvent $event): void
    {
        $this->messageBus->dispatch(
            new Envelope(
                new PushCustomEvent(
                    $event->getEmail(),
                    $event->getSystemName(),
                    $event->getFields(),
                    $event->getChannelCode()
                )
            )
        );
    }
}
