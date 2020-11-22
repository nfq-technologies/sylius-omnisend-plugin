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

class OrderProduct
{
    /** @var string */
    private $productID;

    /** @var string */
    private $sku;

    /** @var string */
    private $variantID;

    /** @var string|null */
    private $variantTitle;

    /** @var string|null */
    private $title;

    /** @var string|null */
    private $vendor;

    /** @var int */
    private $quantity;

    /** @var int */
    private $price;

    /** @var int|null */
    private $discount;

    /** @var int|null */
    private $weight;

    /** @var string|null */
    private $imageUrl;

    /** @var string|null */
    private $productUrl;

    /** @var string[]|array|null */
    private $tags;

    /** @var string[]|array|null */
    private $categoryIDs;

    public function getProductID(): string
    {
        return $this->productID;
    }

    public function setProductID(string $productID): self
    {
        $this->productID = $productID;

        return $this;
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function setSku(string $sku): self
    {
        $this->sku = $sku;

        return $this;
    }

    public function getVariantID(): string
    {
        return $this->variantID;
    }

    public function setVariantID(string $variantID): self
    {
        $this->variantID = $variantID;

        return $this;
    }

    public function getVariantTitle(): ?string
    {
        return $this->variantTitle;
    }

    public function setVariantTitle(?string $variantTitle): self
    {
        $this->variantTitle = $variantTitle;

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

    public function getVendor(): ?string
    {
        return $this->vendor;
    }

    public function setVendor(?string $vendor): self
    {
        $this->vendor = $vendor;

        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDiscount(): ?int
    {
        return $this->discount;
    }

    public function setDiscount(?int $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(?int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): self
    {
        $this->imageUrl = $imageUrl;

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

    public function getTags(): ?array
    {
        return $this->tags;
    }

    public function setTags($tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    public function getCategoryIDs(): ?array
    {
        return $this->categoryIDs;
    }

    public function setCategoryIDs($categoryIDs): self
    {
        $this->categoryIDs = $categoryIDs;

        return $this;
    }
}
