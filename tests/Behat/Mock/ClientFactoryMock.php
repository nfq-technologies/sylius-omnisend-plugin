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

namespace Tests\NFQ\SyliusOmnisendPlugin\Behat\Mock;

use NFQ\SyliusOmnisendPlugin\HttpClient\ClientFactoryInterface;
use Psr\Http\Client\ClientInterface;

class ClientFactoryMock implements ClientFactoryInterface
{
    private LoggableClientMock $loggableClientMock;

    public function __construct(LoggableClientMock $loggableClientMock)
    {
        $this->loggableClientMock = $loggableClientMock;
    }

    public function create(?string $channelCode): ClientInterface
    {
        return $this->loggableClientMock;
    }
}
