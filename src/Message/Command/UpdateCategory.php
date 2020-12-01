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
