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

namespace NFQ\SyliusOmnisendPlugin\Model;

use DateTimeInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Order\Model\OrderInterface;

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
