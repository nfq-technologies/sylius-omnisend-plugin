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

namespace NFQ\SyliusOmnisendPlugin\Client;

use Http\Discovery\MessageFactoryDiscovery;
use NFQ\SyliusOmnisendPlugin\Model\ChannelOmnisendTrackingAwareInterface;
use Psr\Http\Message\RequestInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use \Http\Message\MessageFactory as BaseFactory;

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
                    [
                        AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
                    ]
                ) :
                null
        );
    }
}
