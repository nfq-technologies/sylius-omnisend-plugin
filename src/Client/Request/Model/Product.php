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

class Product
{
    /** @var string|null */
    private $productID;

    /** @var string */
    private $title;

    /** @var string */
    private $status;

    /** @var string|null */
    private $description;

    /** @var string */
    private $currency;

    /** @var string|null */
    private $productUrl;

    /** @var string|null */
    private $vendor;

    /** @var string|null */
    private $type;

    /** @var string|null */
    private $createdAt;

    /** @var string|null */
    private $updatedAt;

    /** @var array|string[]|null */
    private $tags;

    /** @var array|string[]|null */
    private $categoryIDs;

    /** @var array|ProductImage[] */
    private $images;

    /** @var array|ProductImage[] */
    private $variants;

    public function getProductID(): ?string
    {
        return $this->productID;
    }

    public function setProductID(?string $productID): self
    {
        $this->productID = $productID;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

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

    public function getVendor(): ?string
    {
        return $this->vendor;
    }

    public function setVendor(?string $vendor): self
    {
        $this->vendor = $vendor;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?string $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?string $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function setTags($tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    public function getCategoryIDs()
    {
        return $this->categoryIDs;
    }

    public function setCategoryIDs($categoryIDs): self
    {
        $this->categoryIDs = $categoryIDs;

        return $this;
    }

    public function getImages()
    {
        return $this->images;
    }

    public function setImages($images): self
    {
        $this->images = $images;

        return $this;
    }

    public function getVariants()
    {
        return $this->variants;
    }

    public function setVariants($variants): self
    {
        $this->variants = $variants;

        return $this;
    }
}
