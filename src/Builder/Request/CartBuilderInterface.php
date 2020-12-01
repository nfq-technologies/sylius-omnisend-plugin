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

use NFQ\SyliusOmnisendPlugin\Client\Request\Model\Cart;
use NFQ\SyliusOmnisendPlugin\Model\OrderInterface;

interface CartBuilderInterface
{
    public function createCart(): void;

    public function addOrderData(OrderInterface $order): void;

    public function addCustomerData(OrderInterface $order, ?string $contactId = null): void;

    public function addRecoveryUrl(OrderInterface $order): void;

    public function addProducts(OrderInterface $order): void;

    public function getCart(): Cart;
}
