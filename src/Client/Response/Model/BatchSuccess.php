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

class BatchSuccess
{
    /** @var string */
    private $batchID;

    public function getBatchID(): string
    {
        return $this->batchID;
    }

    public function setBatchID(string $batchID): void
    {
        $this->batchID = $batchID;
    }
}
