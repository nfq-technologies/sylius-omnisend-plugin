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

namespace NFQ\SyliusOmnisendPlugin\Client\Request\Model;

class ContactIdentifier
{
    public const TYPE_EMAIL = 'email';
    public const TYPE_PHONE = 'phone';

    /** @var string */
    private $type;

    /** @var string */
    private $id;

    /** @var bool */
    private $sendWelcomeMessage = false;

    /** @var ContactIdentifierChannel[]|array */
    private $channels;

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getChannels()
    {
        return $this->channels;
    }

    public function setChannels($channels): void
    {
        $this->channels = $channels;
    }

    public function addChannel(ContactIdentifierChannel $channels): void
    {
        $this->channels = $channels;
    }

    public function isSendWelcomeMessage(): bool
    {
        return $this->sendWelcomeMessage;
    }

    public function setSendWelcomeMessage(bool $sendWelcomeMessage): void
    {
        $this->sendWelcomeMessage = $sendWelcomeMessage;
    }
}
