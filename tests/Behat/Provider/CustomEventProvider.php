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

namespace Tests\NFQ\SyliusOmnisendPlugin\Behat\Provider;

use NFQ\SyliusOmnisendPlugin\Model\Event;
use NFQ\SyliusOmnisendPlugin\Model\EventField;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class CustomEventProvider
{
    /** @var RepositoryInterface */
    private $eventRepository;

    public function __construct(RepositoryInterface $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public function createEventWithFields(string $systemName, array $fields): Event
    {
        $event = new Event();
        $event->setSystemName($systemName);

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
