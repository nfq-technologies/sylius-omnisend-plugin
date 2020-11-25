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

namespace NFQ\SyliusOmnisendPlugin\Client\Request\Model;

class ProductImage
{
    /** @var string */
    private $imageID;

    /** @var string|null */
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

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getIsDefault(): bool
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
