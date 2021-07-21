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
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Psr\Http\Message\RequestInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;

class MessageFactory
{
    /** @var BaseFactory */
    private $factory;

    /** @var ChannelContextInterface */
    private $channelContext;

    /** @var SerializerInterface */
    private $serializer;

    public function __construct(ChannelContextInterface $channelContext, SerializerInterface $serializer)
    {
        $this->channelContext = $channelContext;
        $this->serializer = $serializer;
    }

    private function getMessageFactory(): BaseFactory
    {
        if ($this->factory === null) {
            $this->factory = MessageFactoryDiscovery::find();
        }

        return $this->factory;
    }

    /** @param mixed $data */
    public function create(string $type, string $url, $data = null): RequestInterface
    {
        return $this->getMessageFactory()->createRequest(
            $type,
            $url,
            [],
            $data !== null ?
                $this->serializer->serialize(
                    $data,
                    'json',
                    SerializationContext::create()->setSerializeNull(false)
                ) :
                null
        );
    }
}
