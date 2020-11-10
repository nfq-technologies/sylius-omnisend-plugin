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

trait ChannelOmnisendTrackingKeyTrait
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", name="omnisend_tracking_key", length=32)
     */
    private $omnisendTrackingKey = false;

    public function isOmnisendTrackingKey(): string
    {
        return $this->omnisendTrackingKey;
    }

    public function setOmnisendTrackingKey(string $omnisendTrackingKey): void
    {
        $this->omnisendTrackingKey = $omnisendTrackingKey;
    }
}
