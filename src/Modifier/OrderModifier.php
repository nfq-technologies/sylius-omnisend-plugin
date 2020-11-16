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

namespace NFQ\SyliusOmnisendPlugin\Modifier;

use NFQ\SyliusOmnisendPlugin\Message\Command\UpdateCart;
use Sylius\Component\Order\Model\OrderInterface;
use Sylius\Component\Order\Model\OrderItemInterface;
use Sylius\Component\Order\Modifier\OrderModifierInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class OrderModifier implements OrderModifierInterface
{
    /** @var OrderModifierInterface */
    private $inner;

    /** @var MessageBusInterface */
    private $messageBus;

    public function __construct(OrderModifierInterface $inner, MessageBusInterface $messageBus)
    {
        $this->inner = $inner;
        $this->messageBus = $messageBus;
    }

    public function addToOrder(OrderInterface $cart, OrderItemInterface $cartItem): void
    {
        $this->inner->addToOrder($cart, $cartItem);
//        $this->messageBus->dispatch(
//            new Envelope(
//                (new UpdateCart())
//                    ->setOrderId($cart->getId())
//                    ->setChannelCode($cart->getChannel()->getCode())
//            )
//        );
    }

    public function removeFromOrder(OrderInterface $cart, OrderItemInterface $item): void
    {
        $this->messageBus->dispatch(
            new Envelope(
                (new UpdateCart())
                    ->setOrderId($cart->getId())
                    ->setChannelCode($cart->getChannel()->getCode())
            )
        );
        $this->inner->removeFromOrder($cart, $item);
    }
}
