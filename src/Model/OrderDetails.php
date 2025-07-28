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

namespace NFQ\SyliusOmnisendPlugin\Model;

use DateTimeInterface;
use Sylius\Component\Order\Model\OrderInterface;
use Sylius\Resource\Model\ResourceInterface;

class OrderDetails implements ResourceInterface
{
    /** @var int */
    private $id;

    /** @var OrderInterface */
    private $order;

    /** @var string|null */
    private $cartId;

    /** @var DateTimeInterface|null */
    private $cancelledAt;

    public function getCancelledAt(): ?DateTimeInterface
    {
        return $this->cancelledAt;
    }

    public function setCancelledAt(?DateTimeInterface $dateTime): void
    {
        $this->cancelledAt = $dateTime;
    }

    public function getCartId(): ?string
    {
        return $this->cartId;
    }

    public function setCartId(?string $cartId): void
    {
        $this->cartId = $cartId;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getOrder(): OrderInterface
    {
        return $this->order;
    }

    public function setOrder(OrderInterface $order): void
    {
        $this->order = $order;
    }
}
