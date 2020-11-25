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

namespace NFQ\SyliusOmnisendPlugin\Builder\Request;

use NFQ\SyliusOmnisendPlugin\Client\Request\Model\Cart;
use NFQ\SyliusOmnisendPlugin\Model\OrderInterface;

class CartBuilderDirector implements CartBuilderDirectorInterface
{
    /** @var CartBuilderInterface */
    private $cartBuilder;

    public function __construct(CartBuilderInterface $cartBuilder)
    {
        $this->cartBuilder = $cartBuilder;
    }

    public function build(OrderInterface $order, ?string $contactId): Cart
    {
        $this->cartBuilder->createCart();
        $this->cartBuilder->addCustomerData($order, $contactId);
        $this->cartBuilder->addOrderData($order);
        $this->cartBuilder->addRecoveryUrl($order);
        $this->cartBuilder->addProducts($order);

        return $this->cartBuilder->getCart();
    }
}
