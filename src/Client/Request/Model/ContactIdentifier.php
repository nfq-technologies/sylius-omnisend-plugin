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

    /** @var ContactIdentifierChannel|null */
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

    public function getChannels(): ?ContactIdentifierChannel
    {
        return $this->channels;
    }

    public function setChannels(ContactIdentifierChannel $channels): void
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
