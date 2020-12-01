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

namespace NFQ\SyliusOmnisendPlugin\Message\Command;

class CreateBatch implements CommandInterface
{
    use CommandTrait;

    const DEFAULT_BATCH_SIZE = 1000;

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
