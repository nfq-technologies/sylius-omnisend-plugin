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

namespace NFQ\SyliusOmnisendPlugin\Client\Request\Model;

class ProductVariant
{
    /** @var string|null */
    private $variantID;

    /** @var string|null */
    private $title;

    /** @var string|null */
    private $sku;

    /** @var string|null */
    private $status;

    /** @var int|null */
    private $price;

    /** @var int|null */
    private $oldPrice;

    /** @var string|null */
    private $productUrl;

    /** @var string|null */
    private $imageID;

    /** @var array|null */
    private $customFields;

    public function getVariantID(): ?string
    {
        return $this->variantID;
    }

    public function setVariantID(?string $variantID): self
    {
        $this->variantID = $variantID;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function setSku(?string $sku): self
    {
        $this->sku = $sku;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getOldPrice(): ?int
    {
        return $this->oldPrice;
    }

    public function setOldPrice(?int $oldPrice): self
    {
        $this->oldPrice = $oldPrice;

        return $this;
    }

    public function getProductUrl(): ?string
    {
        return $this->productUrl;
    }

    public function setProductUrl(?string $productUrl): self
    {
        $this->productUrl = $productUrl;

        return $this;
    }

    public function getImageID(): ?string
    {
        return $this->imageID;
    }

    public function setImageID(?string $imageID): self
    {
        $this->imageID = $imageID;

        return $this;
    }

    public function getCustomFields(): ?array
    {
        return $this->customFields;
    }

    public function setCustomFields(?array $customFields): self
    {
        $this->customFields = $customFields;

        return $this;
    }
}
