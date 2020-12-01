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

use NFQ\SyliusOmnisendPlugin\Builder\Request\EventBuilderDirectorInterface;
use NFQ\SyliusOmnisendPlugin\Client\OmnisendClientInterface;
use NFQ\SyliusOmnisendPlugin\Message\Command\UpdateEvent;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use NFQ\SyliusOmnisendPlugin\Model\Event as BaseEvent;

class UpdateEventHandler implements MessageHandlerInterface
{
    /** @var RepositoryInterface */
    private $eventRepository;

    /** @var EventBuilderDirectorInterface */
    private $eventBuilderDirector;

    /** @var OmnisendClientInterface */
    private $omnisendClient;

    public function __construct(
        RepositoryInterface $eventRepository,
        EventBuilderDirectorInterface $eventBuilderDirector,
        OmnisendClientInterface $omnisendClient
    ) {
        $this->eventRepository = $eventRepository;
        $this->eventBuilderDirector = $eventBuilderDirector;
        $this->omnisendClient = $omnisendClient;
    }

    public function __invoke(UpdateEvent $message): void
    {
        /** @var BaseEvent|null $event */
        $event = $this->eventRepository->findOneBy(['systemName' => $message->getCode()]);
        if (null !== $event) {
            $this->omnisendClient->postEvent(
                $this->eventBuilderDirector->build($event),
                $message->getChannelCode()
            );
        }
    }
}
