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

use Sylius\Component\Core\Model\ChannelInterface as BaseChannelInterface;

interface ChannelOmnisendTrackingAwareInterface extends BaseChannelInterface
{
    public function getOmnisendTrackingKey(): ?string;

    public function setOmnisendTrackingKey(?string $omnisendTrackingKey): void;

    public function getOmnisendApiKey(): ?string;

    public function setOmnisendApiKey(?string $omnisendApiKey): void;
}
