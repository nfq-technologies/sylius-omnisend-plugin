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

class Cart
{
    /** @var string|null */
    private $cartID;

    /** @var string|null */
    private $contactID;

    /** @var string|null */
    private $email;

    /** @var string|null */
    private $phone;

    /** @var string|null */
    private $createdAt;

    /** @var string|null */
    private $updatedAt;

    /** @var string|null */
    private $currency;

    /** @var int */
    private $cartSum;

    /** @var array */
    private $products;

    /** @var string */
    private $cartRecoveryUrl;

    public function __construct()
    {
        $this->products = [];
    }

    public function getCartID(): ?string
    {
        return $this->cartID;
    }

    public function setCartID(?string $cartID): void
    {
        $this->cartID = $cartID;
    }

    public function getContactID(): ?string
    {
        return $this->contactID;
    }

    public function setContactID(?string $contactID): void
    {
        $this->contactID = $contactID;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(?string $currency): void
    {
        $this->currency = $currency;
    }

    public function getCartSum(): int
    {
        return $this->cartSum;
    }

    public function setCartSum(int $cartSum): void
    {
        $this->cartSum = $cartSum;
    }

    public function getProducts(): array
    {
        return $this->products;
    }

    public function addProduct(CartProduct $product): void
    {
        $this->products[] = $product;
    }

    public function setProducts(array $products): void
    {
        $this->products = $products;
    }

    public function getCartRecoveryUrl(): string
    {
        return $this->cartRecoveryUrl;
    }

    public function setCartRecoveryUrl(string $cartRecoveryUrl): void
    {
        $this->cartRecoveryUrl = $cartRecoveryUrl;
    }
}
