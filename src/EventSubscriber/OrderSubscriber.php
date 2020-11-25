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

namespace NFQ\SyliusOmnisendPlugin\EventSubscriber;

use DateTime;
use NFQ\SyliusOmnisendPlugin\Message\Command\CancelOrder;
use NFQ\SyliusOmnisendPlugin\Message\Command\CreateOrder;
use NFQ\SyliusOmnisendPlugin\Message\Command\UpdateOrder;
use NFQ\SyliusOmnisendPlugin\Message\Command\UpdateOrderState;
use NFQ\SyliusOmnisendPlugin\Model\OrderInterface;
use SM\Event\TransitionEvent;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\ShipmentInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Sylius\Component\Order\OrderTransitions;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class OrderSubscriber implements EventSubscriberInterface
{
    /** @var MessageBusInterface */
    private $messageBus;

    /** @var OrderRepositoryInterface */
    private $orderRepository;

    public function __construct(
        MessageBusInterface $messageBus,
        OrderRepositoryInterface $orderRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->messageBus = $messageBus;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.order.post_update' => 'onUpdate',
        ];
    }

    public function onUpdate(ResourceControllerEvent $event): void
    {
        /** @var OrderInterface $order */
        $order = $event->getSubject();
        /** @var ChannelInterface $channel */
        $channel = $order->getChannel();

        if ($order->getState() !== OrderInterface::STATE_CART) {
            $this->messageBus->dispatch(
                new Envelope(
                    new UpdateOrder($order->getId(), $channel->getCode())
                )
            );
        }
    }

    public function onOrderStateChange(TransitionEvent $event, OrderInterface $order): void
    {
        $graph = $event->getStateMachine()->getGraph();
        $transition = $event->getTransition();
        /** @var ChannelInterface $channel */
        $channel = $order->getChannel();

        if ($graph !== OrderTransitions::GRAPH || null === $order->getId()) {
            return;
        }

        switch ($transition) {
            case OrderTransitions::TRANSITION_CREATE:
                $this->messageBus->dispatch(
                    new Envelope(
                        (new CreateOrder($order->getId(), $channel->getCode()))
                    )
                );

                break;
            case OrderTransitions::TRANSITION_CANCEL:
                $order->getOmnisendOrderDetails()->setCancelledAt(new DateTime());
                $this->orderRepository->add($order);

                $this->messageBus->dispatch(
                    new Envelope(
                        new CancelOrder($order->getId(), $channel->getCode())
                    )
                );

                break;
            case OrderTransitions::TRANSITION_FULFILL:
                $this->messageBus->dispatch(
                    new Envelope(
                        (new UpdateOrderState($order->getId(), $channel->getCode()))
                    )
                );

                break;
            default:
                return;
        }
    }

    public function onPaymentStateChange(PaymentInterface $payment): void
    {
        /** @var OrderInterface $order */
        $order = $payment->getOrder();
        /** @var ChannelInterface $channel */
        $channel = $order->getChannel();

        if ($order->getId() !== null) {
            $this->messageBus->dispatch(
                new Envelope(
                    (new UpdateOrderState($order->getId(), $channel->getCode()))
                )
            );
        }
    }

    public function onShipmentStateChange(ShipmentInterface $shipment): void
    {
        /** @var OrderInterface $order */
        $order = $shipment->getOrder();
        /** @var ChannelInterface $channel */
        $channel = $order->getChannel();

        if ($order->getId() !== null) {
            $this->messageBus->dispatch(
                new Envelope(
                    (new UpdateOrderState($order->getId(), $channel->getCode()))
                )
            );
        }
    }
}
