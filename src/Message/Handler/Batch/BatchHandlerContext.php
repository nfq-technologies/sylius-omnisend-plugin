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
