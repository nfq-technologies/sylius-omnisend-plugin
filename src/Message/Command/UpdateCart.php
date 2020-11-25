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

namespace NFQ\SyliusOmnisendPlugin\Message\Command;

class UpdateCart implements CommandInterface
{
    use CommandTrait;

    /** @var int|null */
    private $orderId;

    /** @var string|null */
    private $contactId;

    public function __construct(?int $orderId, ?string $contactId, ?string $channelCode)
    {
        $this->orderId = $orderId;
        $this->contactId = $contactId;
        $this->channelCode = $channelCode;
    }

    public function getOrderId(): ?int
    {
        return $this->orderId;
    }

    public function getContactId(): ?string
    {
        return $this->contactId;
    }
}
