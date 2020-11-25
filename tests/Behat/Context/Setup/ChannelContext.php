<?php

/*
 * This file is part of the NFQ package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\NFQ\SyliusOmnisendPlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Doctrine\Common\Persistence\ObjectManager;
use NFQ\SyliusOmnisendPlugin\Model\ChannelOmnisendTrackingAwareInterface;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Component\Core\Model\ChannelInterface;

class ChannelContext implements Context
{
    /** @var SharedStorageInterface */
    private $sharedStorage;

    /** @var ObjectManager */
    private $channelManager;

    public function __construct(
        SharedStorageInterface $sharedStorage,
        ObjectManager $channelManager
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->channelManager = $channelManager;
    }

    /**
     * @Given the channel :channel has omnisend tracking key with a value :key
     */
    public function theChannelHasOmnisendTrackingKey(ChannelInterface $channel, string $key)
    {
        /** @var ChannelOmnisendTrackingAwareInterface $channel */
        $channel->setOmnisendTrackingKey($key);
        $this->channelManager->flush();
        $this->sharedStorage->set('channel', $channel);
    }

    /**
     * @Given the channel :channel has omnisend api key with a value :key
     */
    public function theChannelHasOmnisendApiKey(ChannelInterface $channel, string $key)
    {
        /** @var ChannelOmnisendTrackingAwareInterface $channel */
        $channel->setOmnisendApiKey($key);
        $this->channelManager->flush();
        $this->sharedStorage->set('channel', $channel);
    }
}
