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

class DeleteProduct implements CommandInterface
{
    use CommandTrait;

    /** @var string|null */
    private $productCode;

    public function __construct(?string $productCode, ?string $channelCode)
    {
        $this->productCode = $productCode;
        $this->channelCode = $channelCode;
    }

    public function getProductCode(): ?string
    {
        return $this->productCode;
    }
}
