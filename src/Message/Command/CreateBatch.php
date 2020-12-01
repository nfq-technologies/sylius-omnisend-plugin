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

namespace NFQ\SyliusOmnisendPlugin\Message\Command;

class CreateBatch implements CommandInterface
{
    use CommandTrait;

    public const DEFAULT_BATCH_SIZE = 1000;

    /** @var string */
    private $localeCode;

    /** @var string */
    private $type;

    /** @var int */
    private $batchSize;

    public function __construct(
        string $type,
        string $channelCode,
        string $localeCode,
        int $batchSize = self::DEFAULT_BATCH_SIZE
    ) {
        $this->localeCode = $localeCode;
        $this->channelCode = $channelCode;
        $this->type = $type;
        $this->batchSize = $batchSize;
    }

    public function getLocaleCode(): string
    {
        return $this->localeCode;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getBatchSize(): int
    {
        return $this->batchSize;
    }
}
