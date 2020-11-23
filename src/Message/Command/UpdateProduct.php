<?php

/*
 * @copyright C UAB NFQ Technologies
 *
 * This Software is the property of NFQ Technologies
 * and is protected by copyright law – it is NOT Freeware.
 *
 * Any unauthorized use of this software without a valid license key
 * is a violation of the license agreement and will be prosecuted by
 * civil and criminal law.
 *
 * Contact UAB NFQ Technologies:
 * E-mail: info@nfq.lt
 * http://www.nfq.lt
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
