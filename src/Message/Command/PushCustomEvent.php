<?php

/*
 * This file is part of the NFQ package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace NFQ\SyliusOmnisendPlugin\Message\Command;

class PushCustomEvent implements CommandInterface
{
    use CommandTrait;

    /** @var string */
    private $email;

    /** @var string */
    private $systemName;

    /** @var array */
    private $fields;

    public function __construct(string $email, string $systemName, array $fields, ?string $channelCode)
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
}

