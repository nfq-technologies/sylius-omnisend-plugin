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

namespace NFQ\SyliusOmnisendPlugin\Client;

use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\MessageFactory as BaseFactory;
use Psr\Http\Message\RequestInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class MessageFactory
{
    private ?BaseFactory $factory = null;

    private ChannelContextInterface $channelContext;

    private SerializerInterface $serializer;

    public function __construct(ChannelContextInterface $channelContext, SerializerInterface $serializer)
    {
        $this->channelContext = $channelContext;
        $this->serializer = $serializer;
    }

    private function getMessageFactory(): BaseFactory
    {
        $this->factory ??= MessageFactoryDiscovery::find();

        return $this->factory;
    }

    public function create(string $type, string $url, mixed $data = null): RequestInterface
    {
        return $this->getMessageFactory()->createRequest(
            $type,
            $url,
            [],
            $data !== null ?
                $this->serializer->serialize(
                    $data,
                    'json',
                    [
                        AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
                    ]
                ) :
                null
        );
    }
}
