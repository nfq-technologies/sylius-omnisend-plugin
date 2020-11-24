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

    public function setOmnisendTrackingKey(string $omnisendTrackingKey): void
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
