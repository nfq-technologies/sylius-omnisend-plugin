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
        $this->cartBuilder->addRequestData($order);
        $this->cartBuilder->addRecoveryUrl($order);
        $this->cartBuilder->addProducts($order);

        return $this->cartBuilder->getCart();
    }
}
