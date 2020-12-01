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

namespace NFQ\SyliusOmnisendPlugin\Builder\Request;

use NFQ\SyliusOmnisendPlugin\Client\Request\Model\CreateEvent;
use NFQ\SyliusOmnisendPlugin\Mapper\EventFieldTypeDefaultValueMapper;
use NFQ\SyliusOmnisendPlugin\Model\Event as BaseEvent;
use NFQ\SyliusOmnisendPlugin\Model\EventField;

class EventBuilder implements EventBuilderInterface
{
    public const DEFAULT_EMAIL = 'sylius@example.com';

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

    public function addEmail(string $email): void
    {
        $this->event->setEmail($email);
    }

    public function addFormattedFields(array $fields): void
    {
        $this->event->setFields($fields);
    }

    public function addSystemName(string $name): void
    {
        $this->event->setSystemName($name);
    }

    public function getEvent(): CreateEvent
    {
        return $this->event;
    }
}
