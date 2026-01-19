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

use NFQ\SyliusOmnisendPlugin\Builder\Request\EventBuilderDirectorInterface;
use NFQ\SyliusOmnisendPlugin\Client\OmnisendClientInterface;
use NFQ\SyliusOmnisendPlugin\Message\Command\UpdateEvent;
use NFQ\SyliusOmnisendPlugin\Model\Event as BaseEvent;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class UpdateEventHandler implements MessageHandlerInterface
{
    /** @var RepositoryInterface */
    private $eventRepository;

    /** @var EventBuilderDirectorInterface */
    private $eventBuilderDirector;

    /** @var OmnisendClientInterface */
    private $omnisendClient;

    /** @var ChannelRepositoryInterface */
    private $channelRepository;

    public function __construct(
        RepositoryInterface $eventRepository,
        EventBuilderDirectorInterface $eventBuilderDirector,
        OmnisendClientInterface $omnisendClient,
        ChannelRepositoryInterface $channelRepository,
    ) {
        $this->eventRepository = $eventRepository;
        $this->eventBuilderDirector = $eventBuilderDirector;
        $this->omnisendClient = $omnisendClient;
        $this->channelRepository = $channelRepository;

    }

    public function __invoke(UpdateEvent $message): void
    {
        if (null === $message->getCode()) {
            return;
        }

        $channel = $this->channelRepository->findOneBy(['code' => $message->getChannelCode()]);
        if (!$channel) {
            return;
        }

        /** @var BaseEvent|null $event */
        $event = $this->eventRepository->findOneBy(['systemName' => $message->getCode(), 'channelId' => $channel->getId()]);

        if (null !== $event) {
            $this->omnisendClient->postEvent(
                $this->eventBuilderDirector->build($event),
                $message->getChannelCode()
            );
        }
    }
}
