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

class UpdateProduct implements CommandInterface
{
    use CommandTrait;

    /** @var int|null */
    private $productId;

    /** @var string|null */
    private $localeCode;

    public function __construct(int $productId, ?string $channelCode, ?string $localeCode = null)
    {
        $this->productId = $productId;
        $this->localeCode = $localeCode;
        $this->channelCode = $channelCode;
    }

    public function getProductId(): ?int
    {
        return $this->productId;
    }

    public function getLocaleCode(): ?string
    {
        return $this->localeCode;
    }
}
