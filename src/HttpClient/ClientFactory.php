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

use Http\Client\Common\Plugin\BaseUriPlugin;
use Http\Client\Common\Plugin\ErrorPlugin;
use Http\Client\Common\Plugin\HeaderDefaultsPlugin;
use Http\Client\Common\PluginClient;
use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\UriFactoryDiscovery;
use Sylius\Component\Channel\Context\CachedPerRequestChannelContext;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Webmozart\Assert\Assert;

/**
 * Class ClientFactory
 * @package Omni\Sylius\OmnisendPlugin\HttpClient
 */
class ClientFactory
{
    /**
     * @param string $apiUrl
     * @param string $apiKey
     * @param CachedPerRequestChannelContext $channelContext
     * @param HttpClient|null $client
     * @return HttpClient
     */
    public static function create(
        string $apiUrl,
        string $apiKey,
        ChannelContextInterface $channelContext = null,
        HttpClient $client = null
    ): HttpClient
    {
        if (null === $client) {
            $client = HttpClientDiscovery::find();
        }

        $headersPlugin = new HeaderDefaultsPlugin([
            'x-api-key' => $apiKey,
        ]);

        $plugins = [
            new BaseUriPlugin(UriFactoryDiscovery::find()->createUri($apiUrl), ['replace' => true]),
            new ErrorPlugin(),
            $headersPlugin
        ];

        return new PluginClient($client, $plugins);
    }
}
