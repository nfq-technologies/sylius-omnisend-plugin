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

namespace NFQ\SyliusOmnisendPlugin\Message\Command;

trait CommandTrait
{
    /** @var string|null */
    private $channelCode;

    public function getChannelCode(): ?string
    {
        return $this->channelCode;
    }
}
