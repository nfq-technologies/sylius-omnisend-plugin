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

namespace NFQ\SyliusOmnisendPlugin\Builder\Request;

use NFQ\SyliusOmnisendPlugin\Client\Request\Model\CreateEvent;
use NFQ\SyliusOmnisendPlugin\Mapper\EventFieldTypeDefaultValueMapper;
use NFQ\SyliusOmnisendPlugin\Model\Event as BaseEvent;
use NFQ\SyliusOmnisendPlugin\Model\EventField;

class EventBuilder implements EventBuilderInterface
{
    const DEFAULT_EMAIL = 'sylius@example.com';

    /** @var CreateEvent */
    private $event;

    public function createEvent(): void
    {
        $this->event = new CreateEvent();
    }

    public function addMainData(BaseEvent $event): void
    {
        $this->event->setSystemName($event->getSystemName());
        $this->event->setName($event->getName());
        $this->event->setEmail(self::DEFAULT_EMAIL);
    }

    public function addFields(BaseEvent $event): void
    {
        $fields = [];

        /** @var EventField $field */
        foreach ($event->getFields() as $field) {
            $value = EventFieldTypeDefaultValueMapper::map($field->getType());

            if ($value) {
                $fields[$field->getSystemName()] = $value;
            }
        }

        $this->event->setFields($fields);
    }

    public function getEvent(): CreateEvent
    {
        return $this->event;
    }
}
