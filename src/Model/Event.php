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

namespace NFQ\SyliusOmnisendPlugin\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;
use Sylius\Component\Resource\Model\TimestampableTrait;

class Event implements ResourceInterface, TimestampableInterface
{
    use TimestampableTrait;

    /** @var int */
    private $id;

    /** @var string|null */
    private $eventID;

    /** @var string|null */
    private $name;

    /** @var string|null */
    private $systemName;

    /** @var bool */
    private $enabled;

    /** @var EventField[]|Collection */
    private $fields;

    /** @var ChannelInterface|null */
    private $channel;

    public function __construct()
    {
        $this->fields = new ArrayCollection();
        $this->enabled = true;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEventID(): ?string
    {
        return $this->eventID;
    }

    public function setEventID(?string $eventID): void
    {
        $this->eventID = $eventID;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getSystemName(): ?string
    {
        return $this->systemName;
    }

    public function setSystemName(?string $systemName): void
    {
        $this->systemName = $systemName;
    }

    public function isEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    public function getFields(): Collection
    {
        return $this->fields;
    }

    public function setFields(Collection $fields): void
    {
        foreach ($fields as $field) {
            $this->addField($field);
        }
    }

    public function addField(EventField $eventField): void
    {
        if (!$this->hasField($eventField)) {
            $eventField->setEvent($this);
            $this->fields->add($eventField);
        }
    }

    public function removeFields(): void
    {
        foreach ($this->fields as $field) {
            $this->removeField($field);
        }
    }

    public function hasField(EventField $eventField): bool
    {
        return $this->fields->contains($eventField);
    }

    public function removeField(EventField $eventField): void
    {
        if ($this->hasField($eventField)) {
            $this->fields->removeElement($eventField);
        }
    }

    public function getFieldBySystemName(string $systemName): ?EventField
    {
        $field = $this->fields->filter(
            function (EventField $eventField) use ($systemName): bool {
                return $eventField->getSystemName() === $systemName;
            }
        )->first();

        return $field ? $field : null;
    }

    public function getChannel(): ?ChannelInterface
    {
        return $this->channel;
    }

    public function setChannel(?ChannelInterface $channel): void
    {
        $this->channel = $channel;
    }
}
