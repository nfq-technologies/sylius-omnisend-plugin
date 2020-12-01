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

class UpdateCategory implements CommandInterface
{
    use CommandTrait;

    /** @var string|null */
    private $taxonCode;

    public function __construct(?string $taxonCode, ?string $channelCode)
    {
        $this->taxonCode = $taxonCode;
        $this->channelCode = $channelCode;
    }

    public function getTaxonCode(): ?string
    {
        return $this->taxonCode;
    }
}
