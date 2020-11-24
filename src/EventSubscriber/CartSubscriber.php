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

namespace NFQ\SyliusOmnisendPlugin\EventSubscriber;

use NFQ\SyliusOmnisendPlugin\Message\Command\DeleteCart;
use NFQ\SyliusOmnisendPlugin\Message\Command\UpdateCart;
use NFQ\SyliusOmnisendPlugin\Resolver\ContactIdResolverInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class CartSubscriber implements EventSubscriberInterface
{
    /** @var MessageBusInterface */
    private $messageBus;

    /** @var ContactIdResolverInterface */
    private $contactIdResolver;

    /** @var CartContextInterface */
    private $cartContext;

    public function __construct(
        MessageBusInterface $messageBus,
        CartContextInterface $cartContext,
        ContactIdResolverInterface $contactIdResolver
    ) {
        $this->messageBus = $messageBus;
        $this->cartContext = $cartContext;
        $this->contactIdResolver = $contactIdResolver;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.order_item.post_add' => 'onOrderItemChange',
            'sylius.order_item.post_remove' => 'onOrderItemChange',
            'sylius.order.post_update' => 'onUpdate',
            'sylius.carts.post_remove' => 'onCartsRemove',
        ];
    }

    public function onOrderItemChange(ResourceControllerEvent $event): void
    {
        /** @var \NFQ\SyliusOmnisendPlugin\Model\OrderInterface $cart */
        $cart = $this->cartContext->getCart();

        $this->messageBus->dispatch(
            new Envelope(
                (new UpdateCart())
                    ->setOrderId($cart->getId())
                    ->setChannelCode($cart->getChannel()->getCode())
                    ->setContactId($this->contactIdResolver->resolve($cart))
            )
        );
    }

    public function updateOrder(OrderInterface $order): void
    {
        $this->messageBus->dispatch(
            new Envelope(
                (new UpdateCart())
                    ->setOrderId($order->getId())
                    ->setChannelCode($order->getChannel()->getCode())
                    ->setContactId($this->contactIdResolver->resolve($order))
            )
        );
    }

    public function onUpdate(ResourceControllerEvent $event): void
    {
        /** @var \NFQ\SyliusOmnisendPlugin\Model\OrderInterface $order */
        $order = $event->getSubject();

        if ($order->getState() === OrderInterface::STATE_CART) {
            $this->messageBus->dispatch(
                new Envelope(
                    (new UpdateCart())
                        ->setOrderId($order->getId())
                        ->setChannelCode($order->getChannel()->getCode())
                        ->setContactId($this->contactIdResolver->resolve($order))
                )
            );
        }
    }

    public function onCartsRemove(GenericEvent $event): void
    {
        /** @var array|\NFQ\SyliusOmnisendPlugin\Model\OrderInterface[] $carts */
        $carts = $event->getSubject();

        foreach ($carts as $cart) {
            if ($cart->getOmnisendOrderDetails()->getCartId()) {
                $this->messageBus->dispatch(
                    new Envelope(
                        (new DeleteCart())
                            ->setOmnisendCartId($cart->getOmnisendOrderDetails()->getCartId())
                            ->setChannelCode($cart->getChannel()->getCode())
                    )
                );
            }
        }
    }
}
