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

namespace Omni\Sylius\OmnisendPlugin\HttpClient;

use GuzzleHttp\Psr7\Response;
use Http\Client\Exception\HttpException;
use Http\Client\HttpClient;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\MessageFactory;
use Omni\Sylius\OmnisendPlugin\Model\Traits\OmnisendAwareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Channel\Context\ChannelNotFoundException;
use Sylius\Component\Channel\Model\ChannelInterface;

/**
 * Class Client
 * @package Omni\Sylius\OmnisendPlugin\HttpClient
 */
class Client implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var MessageFactory|null
     */
    private $messageFactory;

    /**
     * @var bool
     */
    private $testMode;

    /**
     * @var string
     */
    private $omnisendServiceUrl;

    /**
     * Client constructor.
     * @param string $omnisendServiceUrl
     * @param bool $testMode
     */
    public function __construct(string $omnisendServiceUrl, bool $testMode = false)
    {
        $this->omnisendServiceUrl = $omnisendServiceUrl;
        $this->testMode = $testMode;
    }

    /**
     * @param ChannelContextInterface|null $channelContext
     */
    public function initializeOmnisendClient(ChannelContextInterface $channelContext): void
    {
        try {
            $this->recreateOmnisendClient($channelContext->getChannel());
        } catch (ChannelNotFoundException $exception) {
            $this->httpClient = null;
        }
    }

    /**
     * @param ChannelInterface|OmnisendAwareInterface $channel
     */
    public function recreateOmnisendClient(ChannelInterface $channel): void
    {
        $apiKey = $channel->getOmnisendApiKey();

        if (empty($apiKey)) {
            return;
        }

        $this->httpClient = ClientFactory::create($this->omnisendServiceUrl, $apiKey);
    }

    /**
     * @param string $path
     * @param string|null $body
     * @return null|ResponseInterface
     * @throws \Exception
     * @throws \Http\Client\Exception
     */
    public function post(string $path, string $body = null): ?ResponseInterface
    {
        if ($this->testMode) {
            return new Response();
        }

        return $this->sendRequest(
            'POST',
            $path,
            ['Content-Type' => 'application/json'],
            $body
        );
    }

    /**
     * @param string $method
     * @param string $path
     * @param array $headers
     * @param null $body
     * @return null|ResponseInterface
     * @throws \Exception
     * @throws \Http\Client\Exception
     */
    private function sendRequest(string $method, string $path, array $headers = [], $body = null): ?ResponseInterface
    {
        $request = $this->getMessageFactory()->createRequest($method, $path, $headers, $body);

        try {
            return $this->httpClient->sendRequest($request);
        } catch (HttpException $requestException) {
            $response = null;
            if (null !== $requestException->getResponse()) {
                $response = [
                    'status' => $requestException->getResponse()->getStatusCode(),
                    'headers' => $requestException->getResponse()->getHeaders(),
                    'partial_body' => $requestException->getResponse()->getBody()->read(255),
                ];
            }

            $this->logger->critical(
                'Request to Omnisend API failed.',
                [
                    'request_data' => $body,
                    'response' => $response,
                ]
            );

            return null;
        }
    }

    /**
     * @return MessageFactory
     */
    private function getMessageFactory(): MessageFactory
    {
        if ($this->messageFactory === null) {
            $this->messageFactory = MessageFactoryDiscovery::find();
        }

        return $this->messageFactory;
    }
}
