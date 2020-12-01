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
