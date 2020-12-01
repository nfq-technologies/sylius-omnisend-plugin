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

class Category
{
    /** @var string|null */
    private $categoryID;

    /** @var string|null */
    private $title;

    /** @var string|null */
    private $createdAt;

    /** @var string|null */
    private $updatedAt;

    public function getCategoryID(): ?string
    {
        return $this->categoryID;
    }

    public function setCategoryID(?string $categoryID): self
    {
        $this->categoryID = $categoryID;

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

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?string $createAt): self
    {
        $this->createdAt = $createAt;

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
}
