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

namespace NFQ\SyliusOmnisendPlugin\Model;

use Sylius\Component\Resource\Model\ResourceInterface;

class EventField implements ResourceInterface
{
    public const TYPE_INT = 'int';

    public const TYPE_FLOAT = 'float';

    public const TYPE_BOOL = 'bool';

    public const TYPE_STRING = 'string';

    public const TYPE_EMAIL = 'email';

    public const TYPE_URL = 'url';

    public const TYPE_DATE = 'date';

    public const TYPE_DATETIME = 'dateTime';

    public const TYPES = [
        self::TYPE_INT,
        self::TYPE_FLOAT,
        self::TYPE_BOOL,
        self::TYPE_STRING,
        self::TYPE_EMAIL,
        self::TYPE_URL,
        self::TYPE_DATE,
        self::TYPE_DATETIME,
    ];

    /** @var int */
    private $id;

    /** @var string */
    private $name;

    /** @var string */
    private $systemName;

    /** @var string */
    private $type;

    /** @var bool */
    private $required;

    /** @var Event|null */
    private $event;

    public function __construct()
    {
        $this->required = false;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getSystemName(): ?string
    {
        return $this->systemName;
    }

    public function setSystemName(string $systemName): void
    {
        $this->systemName = $systemName;
    }

    public function isRequired(): ?bool
    {
        return $this->required;
    }

    public function setRequired(bool $required): void
    {
        $this->required = $required;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): void
    {
        $this->event = $event;
    }
}
