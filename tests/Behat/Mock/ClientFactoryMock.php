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

namespace Tests\NFQ\SyliusOmnisendPlugin\Behat\Mock;

use Http\Client\HttpClient;
use NFQ\SyliusOmnisendPlugin\HttpClient\ClientFactoryInterface;

class ClientFactoryMock implements ClientFactoryInterface
{
    /** @var LoggableClientMock */
    private $loggableClientMock;

    public function __construct(LoggableClientMock $loggableClientMock)
    {
        $this->loggableClientMock = $loggableClientMock;
    }

    public function create(?string $channelCode): HttpClient
    {
        return $this->loggableClientMock;
    }
}
