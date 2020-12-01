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

class UpdateEvent implements CommandInterface
{
    use CommandTrait;

    /** @var string|null */
    private $code;

    public function __construct(?string $code, ?string $channelCode)
    {
        $this->code = $code;
        $this->channelCode = $channelCode;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }
}
