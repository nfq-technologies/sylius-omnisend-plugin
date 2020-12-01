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

namespace NFQ\SyliusOmnisendPlugin\Event;

use Symfony\Contracts\EventDispatcher\Event;

class CustomEvent extends Event
{
    /** @var string */
    private $email;

    /** @var string */
    private $systemName;

    /** @var array */
    private $fields;

    /** @var string */
    private $channelCode;

    public function __construct(string $email, string $systemName, string $channelCode, array $fields)
    {
        $this->email = $email;
        $this->systemName = $systemName;
        $this->fields = $fields;
        $this->channelCode = $channelCode;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getSystemName(): string
    {
        return $this->systemName;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function getChannelCode(): string
    {
        return $this->channelCode;
    }
}
