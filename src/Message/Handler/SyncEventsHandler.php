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

use Doctrine\ORM\EntityManagerInterface;
use NFQ\SyliusOmnisendPlugin\Client\OmnisendClientInterface;
use NFQ\SyliusOmnisendPlugin\Message\Command\SyncEvents;
use NFQ\SyliusOmnisendPlugin\Model\Event;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class SyncEventsHandler implements MessageHandlerInterface
{
    /** @var RepositoryInterface */
    private $eventRepository;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var OmnisendClientInterface */
    private $omnisendClient;

    /** @var ChannelRepositoryInterface */
    private $channelRepository;

    public function __construct(
        RepositoryInterface $eventRepository,
        EntityManagerInterface $entityManager,
        OmnisendClientInterface $omnisendClient,
        ChannelRepositoryInterface $channelRepository
    ) {
        $this->channelRepository = $channelRepository;
        $this->eventRepository = $eventRepository;
        $this->entityManager = $entityManager;
        $this->omnisendClient = $omnisendClient;
    }

    public function __invoke(SyncEvents $message): void
    {
        /** @var Event[]|null $events */
        $events = $this->omnisendClient->getEvents($message->getChannelCode());

        if (null !== $events) {
            $channel = $this->channelRepository->findOneBy(['code' => $message->getChannelCode()]);
            foreach ($events as $event) {
                /** @var Event|null $systemEvent */
                $systemEvent = $this->eventRepository->findOneBy(['systemName' => $event->getSystemName()]);
                if (null !== $systemEvent) {
                    $systemEvent->setEventID($event->getEventID());
                    $systemEvent->setName($event->getName());
                    $systemEvent->setEnabled((bool)$event->isEnabled());
                    $systemEvent->removeFields();
                    foreach ($event->getFields() as $field) {
                        $systemEvent->addField($field);
                    }
                    $this->entityManager->persist($systemEvent);
                } else {
                    $event->setChannel($channel);
                    $this->entityManager->persist($event);
                }
            }

            $this->entityManager->flush();
        }
    }
}
