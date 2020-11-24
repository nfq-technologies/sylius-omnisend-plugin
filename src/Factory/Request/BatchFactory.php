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
