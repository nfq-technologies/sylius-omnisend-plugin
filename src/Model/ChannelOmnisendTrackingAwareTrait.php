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

namespace NFQ\SyliusOmnisendPlugin\Model;

trait ChannelOmnisendTrackingAwareTrait
{
    /**
     * @var string|null
     *
     * @ORM\Column(type="string", name="omnisend_tracking_key", length=32, nullable=true)
     */
    private $omnisendTrackingKey;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", name="omnisend_api_key", nullable=true)
     */
    private $omnisendApiKey;

    public function getOmnisendTrackingKey(): ?string
    {
        return $this->omnisendTrackingKey;
    }

    public function setOmnisendTrackingKey(?string $omnisendTrackingKey): void
    {
        $this->omnisendTrackingKey = $omnisendTrackingKey;
    }

    public function getOmnisendApiKey(): ?string
    {
        return $this->omnisendApiKey;
    }

    public function setOmnisendApiKey(?string $omnisendApiKey): void
    {
        $this->omnisendApiKey = $omnisendApiKey;
    }
}
