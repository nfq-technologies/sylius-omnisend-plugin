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

namespace NFQ\SyliusOmnisendPlugin\Builder\Request;

use NFQ\SyliusOmnisendPlugin\Client\Request\Model\Order;
use Sylius\Component\Core\Model\OrderInterface;

interface OrderBuilderInterface
{
    public function createOrder(): void;

    public function addOrderData(OrderInterface $order): void;

    public function addTotals(OrderInterface $order): void;

    public function addCartData(OrderInterface $order): void;

    public function addProducts(OrderInterface $order): void;

    public function addTrackingData(OrderInterface $order): void;

    public function addStates(OrderInterface $order): void;

    public function addAddresses(OrderInterface $order): void;

    public function addUpdateAt(OrderInterface $order): void;

    public function addCancelData(OrderInterface $order): void;

    public function addCouponData(OrderInterface $order): void;

    public function getOrder(): Order;
}
