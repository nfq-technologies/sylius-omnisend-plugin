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

    public function __construct(string $email, string $systemName,  string $channelCode, array $fields)
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
