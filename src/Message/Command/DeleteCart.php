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

class DeleteCart implements CommandInterface
{
    use CommandTrait;

    /** @var string|null */
    private $omnisendCartId;

    public function __construct(?string $omnisendCartId, ?string $channelCode)
    {
        $this->omnisendCartId = $omnisendCartId;
        $this->channelCode = $channelCode;
    }

    public function getOmnisendCartId(): ?string
    {
        return $this->omnisendCartId;
    }
}
