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
