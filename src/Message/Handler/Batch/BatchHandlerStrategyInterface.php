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

namespace NFQ\SyliusOmnisendPlugin\Message\Handler\Batch;

use NFQ\SyliusOmnisendPlugin\Message\Command\CreateBatch;

interface BatchHandlerStrategyInterface
{
    public function support(CreateBatch $batch): bool;

    public function handle(CreateBatch $batch): void;
}
