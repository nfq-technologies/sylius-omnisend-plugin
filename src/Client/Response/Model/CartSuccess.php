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

namespace NFQ\SyliusOmnisendPlugin\Client\Response\Model;

class CartSuccess
{
    /** @var string|null */
    private $email;

    /** @var string|null */
    private $cartID;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCartID(): ?string
    {
        return $this->cartID;
    }

    public function setCartID(?string $cartID): self
    {
        $this->cartID = $cartID;

        return $this;
    }
}
