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

namespace NFQ\SyliusOmnisendPlugin\Builder\Request;

use NFQ\SyliusOmnisendPlugin\Client\Request\Model\Order;
use NFQ\SyliusOmnisendPlugin\Model\OrderInterface;

interface OrderBuilderInterface
{
    public function createOrder(): void;

    public function addOrderData(OrderInterface $order): void;

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
