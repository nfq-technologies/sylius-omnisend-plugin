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

namespace NFQ\SyliusOmnisendPlugin\Client\Response\Model;

class BatchDataSuccess
{
    /** @var string */
    private $batchID;

    /** @var string */
    private $status;
    
    /** @var string */
    private $endpoint;

    /** @var int */
    private $totalCount;

    /** @var int */
    private $finishedCount;
    
    /** @var int */
    private $errorsCount;

    public function getBatchID(): string
    {
        return $this->batchID;
    }

    public function setBatchID(string $batchID): void
    {
        $this->batchID = $batchID;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    public function setEndpoint(string $endpoint): void
    {
        $this->endpoint = $endpoint;
    }

    public function getTotalCount(): int
    {
        return $this->totalCount;
    }

    public function setTotalCount(int $totalCount): void
    {
        $this->totalCount = $totalCount;
    }

    public function getFinishedCount(): int
    {
        return $this->finishedCount;
    }

    public function setFinishedCount(int $finishedCount): void
    {
        $this->finishedCount = $finishedCount;
    }

    public function getErrorsCount(): int
    {
        return $this->errorsCount;
    }

    public function setErrorsCount(int $errorsCount): void
    {
        $this->errorsCount = $errorsCount;
    }
}
