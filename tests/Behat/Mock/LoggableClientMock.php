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

use GuzzleHttp\Psr7\Response;
use Http\Client\HttpClient;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class LoggableClientMock implements HttpClient
{
    public const FILE = 'nfq_sylius_omnisend_last_request.json';

    /** @var string */
    private $cacheDir;

    public function __construct(string $cacheDir)
    {
        $this->cacheDir = $cacheDir;
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $result = [
            'method' => $request->getMethod(),
            'url' => $request->getUri()->getPath(),
            'body' => json_decode($request->getBody()->getContents())
        ];

        file_put_contents($this->getFile(), json_encode($result));

        return new Response(200);
    }

    public function getLastRequestData(): ?array
    {
        return file_exists($this->getFile()) ? json_decode(file_get_contents($this->getFile()), true) : null;
    }

    public function getRawLastRequestData(): ?string
    {
        return file_exists($this->getFile()) ? file_get_contents($this->getFile()) : null;
    }

    public function getFile()
    {
        return $this->cacheDir . '/' . self::FILE;
    }
}
