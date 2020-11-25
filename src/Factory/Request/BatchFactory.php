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

namespace NFQ\SyliusOmnisendPlugin\Factory\Request;

use NFQ\SyliusOmnisendPlugin\Client\Request\Model\Batch;

class BatchFactory implements BatchFactoryInterface
{
    public function create(string $method, string $type, array $data, ?string $eventId = null): Batch
    {
        return (new Batch())
            ->setEndpoint($type)
            ->setMethod($method)
            ->setEventID($eventId)
            ->setItems($data);
    }
}
