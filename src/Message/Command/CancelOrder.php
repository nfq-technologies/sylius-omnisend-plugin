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

class CancelOrder implements CommandInterface
{
    use CommandTrait;

    /** @var int */
    private $orderId;

    public function __construct(int $orderId, ?string $channelCode)
    {
        $this->channelCode = $channelCode;
        $this->orderId = $orderId;
    }

    public function getOrderId(): int
    {
        return $this->orderId;
    }
}
