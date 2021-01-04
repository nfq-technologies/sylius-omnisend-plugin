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

class UpdateContact implements CommandInterface
{
    use CommandTrait;

    /** @var int */
    private $customerId;

    public function __construct(int $customerId, ?string $channelCode)
    {
        $this->customerId = $customerId;
        $this->channelCode = $channelCode;
    }

    public function getCustomerId(): int
    {
        return $this->customerId;
    }
}
