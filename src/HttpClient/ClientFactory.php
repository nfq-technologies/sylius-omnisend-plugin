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

namespace NFQ\SyliusOmnisendPlugin\HttpClient;

use Http\Client\Common\Exception\ClientErrorException;
use Http\Client\Common\Plugin\BaseUriPlugin;
use Http\Client\Common\Plugin\ErrorPlugin;
use Http\Client\Common\Plugin\HeaderDefaultsPlugin;
use Http\Client\Common\Plugin\RetryPlugin;
use Http\Client\Common\PluginClient;
use Http\Client\Exception;
use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\UriFactoryDiscovery;
use NFQ\SyliusOmnisendPlugin\Model\ChannelOmnisendTrackingAwareInterface;
use Psr\Http\Message\RequestInterface;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ClientFactory implements ClientFactoryInterface
{
    public const ENDPOINT = 'https://api.omnisend.com/';

    /** @var ChannelRepositoryInterface */
    private $channelRepository;

    public function __construct(ChannelRepositoryInterface $channelRepository)
    {
        $this->channelRepository = $channelRepository;
    }

    public function create(?string $channelCode): HttpClient
    {
        $channel = null;
        if (null !== $channelCode) {
            /** @var ChannelOmnisendTrackingAwareInterface $channel */
            $channel = $this->channelRepository->findOneByCode($channelCode);
        }
        $client = HttpClientDiscovery::find();

        $headersPlugin = new HeaderDefaultsPlugin(
            [
                'Content-Type' => 'application/json',
                'X-API-KEY' => $channel !== null ? $channel->getOmnisendApiKey() : null,
            ]
        );

        $retryPlugin = new RetryPlugin(
            [
                'retries' => 10,
                'exception_decider' => static function (RequestInterface $request, Exception $e): bool {
                    if (!$e instanceof ClientErrorException) {
                        return false;
                    }

                    return $e->getResponse()->getStatusCode() === Response::HTTP_TOO_MANY_REQUESTS;
                }
            ]
        );

        $plugins = [
            new BaseUriPlugin(UriFactoryDiscovery::find()->createUri(self::ENDPOINT), ['replace' => true]),
            $retryPlugin,
            new ErrorPlugin(),
            $headersPlugin,
        ];

        return new PluginClient($client, $plugins);
    }
}
