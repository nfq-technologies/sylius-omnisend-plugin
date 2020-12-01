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

namespace NFQ\SyliusOmnisendPlugin\Message\Handler\Batch;

use NFQ\SyliusOmnisendPlugin\Message\Command\CreateBatch;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class BatchHandlerContext implements MessageHandlerInterface
{
    /** @var iterable|BatchHandlerStrategyInterface[] */
    private $strategies;

    public function __construct(iterable $strategies)
    {
        $this->strategies = $strategies;
    }

    public function __invoke(CreateBatch $batch): void
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->support($batch)) {
                $strategy->handle($batch);

                return;
            }
        }
    }
}
