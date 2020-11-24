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

namespace Tests\NFQ\SyliusOmnisendPlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Sylius\Component\Core\Model\ChannelInterface;
use Tests\NFQ\SyliusOmnisendPlugin\Behat\Page\Admin\Channel\UpdatePage;
use Webmozart\Assert\Assert;

class ManagingChannelContext implements Context
{
    /**
     * @var UpdatePage
     */
    private $channelUpdatePage;

    public function __construct(UpdatePage $channelUpdatePage)
    {
        $this->channelUpdatePage = $channelUpdatePage;
    }

    /**
     * @When I set omnisend tracking key :key
     */
    public function iSetOmnisendTrackingKey(string $key): void
    {
        $this->channelUpdatePage->setOmnisendTrackingKey($key);
    }

    /**
     * @When I set omnisend api key :key
     */
    public function iSetOmnisendApiKey(string $key): void
    {
        $this->channelUpdatePage->setOmnisendApiKey($key);
    }

    /**
     * @Given /^(channel "([^"]+)") should have omnisend tracking key with value "([^"]+)"$/
     */
    public function thisChannelOmnisendTrackingKeyShouldBe(
        ChannelInterface $channel,
        string $channelCode,
        string $omnisendTrackingKey
    ): void {
        if (!$this->channelUpdatePage->isOpen(['id' => $channel->getId()])) {
            $this->channelUpdatePage->open(['id' => $channel->getId()]);
        }

        Assert::same($this->channelUpdatePage->getOmnisendTrackingKey(), $omnisendTrackingKey);
    }

    /**
     * @Given /^(channel "([^"]+)") should have omnisend api key with value "([^"]+)"$/
     */
    public function thisChannelOmnisendApiKeyShouldBe(
        ChannelInterface $channel,
        string $channelCode,
        string $key
    ): void {
        if (!$this->channelUpdatePage->isOpen(['id' => $channel->getId()])) {
            $this->channelUpdatePage->open(['id' => $channel->getId()]);
        }

        Assert::same($this->channelUpdatePage->getOmnisendApiKey(), $key);
    }
}
