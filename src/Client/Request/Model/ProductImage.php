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

namespace NFQ\SyliusOmnisendPlugin\Client\Request\Model;

class ProductImage
{
    /** @var string */
    private $imageID;

    /** @var string */
    private $url;

    /** @var bool */
    private $isDefault;

    /** @var array */
    private $variantIDs;

    public function getImageID(): string
    {
        return $this->imageID;
    }

    public function setImageID(string $imageID): self
    {
        $this->imageID = $imageID;

        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function isDefault(): bool
    {
        return $this->isDefault;
    }

    public function setIsDefault(bool $isDefault): self
    {
        $this->isDefault = $isDefault;

        return $this;
    }

    public function getVariantIDs(): array
    {
        return $this->variantIDs;
    }

    public function setVariantIDs(array $variantIDs): self
    {
        $this->variantIDs = $variantIDs;

        return $this;
    }
}
