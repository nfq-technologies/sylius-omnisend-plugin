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

use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class LoggableClientMock implements ClientInterface
{
    public const FILE = 'nfq_sylius_omnisend_last_request.json';

    private string $cacheDir;

    public function __construct(string $cacheDir)
    {
        $this->cacheDir = $cacheDir;
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $result = [
            'method' => $request->getMethod(),
            'url' => $request->getUri()->getPath(),
            'body' => json_decode($request->getBody()->getContents()),
        ];

        file_put_contents($this->getFile(), json_encode($result));

        return Psr17FactoryDiscovery::findResponseFactory()->createResponse(200);
    }

    public function getFile(): string
    {
        return $this->cacheDir . '/' . self::FILE;
    }

    public function getLastRequestData(): ?array
    {
        return file_exists($this->getFile()) ? json_decode(file_get_contents($this->getFile()), true) : null;
    }

    public function getRawLastRequestData(): ?string
    {
        return file_exists($this->getFile()) ? file_get_contents($this->getFile()) : null;
    }
}
