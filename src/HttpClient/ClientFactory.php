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

namespace NFQ\SyliusOmnisendPlugin\HttpClient;

use Http\Client\Common\Plugin\BaseUriPlugin;
use Http\Client\Common\Plugin\ErrorPlugin;
use Http\Client\Common\Plugin\HeaderDefaultsPlugin;
use Http\Client\Common\PluginClient;
use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\UriFactoryDiscovery;
use NFQ\SyliusOmnisendPlugin\Model\ChannelOmnisendTrackingAwareInterface;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;

class ClientFactory
{
    public const ENDPOINT = 'https://api.omnisend.com/';

    /** @var ChannelRepositoryInterface */
    private $channelRepository;

    public function __construct(ChannelRepositoryInterface $channelRepository)
    {
        $this->channelRepository = $channelRepository;
    }

    public function create(string $channelCode): HttpClient
    {
        /** @var ChannelOmnisendTrackingAwareInterface $channel */
        $channel = $this->channelRepository->findOneByCode($channelCode);
        $client = HttpClientDiscovery::find();

        /**
         * 5c5af4578653ed7d78a067e5-EgZ7yp8GF0TV49JBSo7a0xRv2hjP2vmZkbTEi5xq327uK4pnxj
         */
        $headersPlugin = new HeaderDefaultsPlugin(
            [
                'Content-Type' => 'application/json',
                'X-API-KEY' => '5c5af4578653ed7d78a067e5-EgZ7yp8GF0TV49JBSo7a0xRv2hjP2vmZkbTEi5xq327uK4pnxj',
            ]
        );

        $plugins = [
            new BaseUriPlugin(UriFactoryDiscovery::find()->createUri(self::ENDPOINT), ['replace' => true]),
            new ErrorPlugin(),
            $headersPlugin
        ];

        return new PluginClient($client, $plugins);
    }
}
