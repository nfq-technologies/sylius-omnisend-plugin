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

namespace Tests\NFQ\SyliusOmnisendPlugin\Behat\Provider;

use NFQ\SyliusOmnisendPlugin\Model\Event;
use NFQ\SyliusOmnisendPlugin\Model\EventField;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class CustomEventProvider
{
    /** @var RepositoryInterface */
    private $eventRepository;

    /** @var ChannelContextInterface */
    private $channelContext;

    public function __construct(
        RepositoryInterface $eventRepository,
        ChannelContextInterface $channelContext
    ) {
        $this->channelContext = $channelContext;
        $this->eventRepository = $eventRepository;
    }

    public function createEventWithFields(string $systemName, array $fields): Event
    {
        $event = new Event();
        $event->setSystemName($systemName);
        $event->setChannel($this->channelContext->getChannel());
        foreach ($fields as $key => $type) {
            $eventField = new EventField();
            $eventField->setSystemName($key);
            $eventField->setType($type);
            $event->addField($eventField);
        }
        $this->eventRepository->add($event);

        return $event;
    }
}
