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

use NFQ\SyliusOmnisendPlugin\Message\Command\CancelOrder;
use NFQ\SyliusOmnisendPlugin\Message\Command\CreateOrder;
use NFQ\SyliusOmnisendPlugin\Message\Command\UpdateOrder;
use NFQ\SyliusOmnisendPlugin\Message\Command\UpdateOrderState;
use NFQ\SyliusOmnisendPlugin\Model\OrderInterface;
use SM\Event\TransitionEvent;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\ShipmentInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Sylius\Component\Order\OrderTransitions;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use DateTime;

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
            'sylius.order.post_update' => 'onUpdate'
        ];
    }

    public function onUpdate(ResourceControllerEvent $event)
    {
        /** @var OrderInterface $order */
        $order = $event->getSubject();

        if ($order->getState() !== OrderInterface::STATE_CART) {
            $this->messageBus->dispatch(
                new Envelope(
                    new UpdateOrder($order->getId(), $order->getChannel()->getCode())
                )
            );
        }
    }

    public function onOrderStateChange(TransitionEvent $event, OrderInterface $order): void
    {
        $graph = $event->getStateMachine()->getGraph();
        $transition = $event->getTransition();

        if ($graph !== OrderTransitions::GRAPH || null === $order->getId()) {
            return;
        }

        switch ($transition) {
            case OrderTransitions::TRANSITION_CREATE:
                $this->messageBus->dispatch(
                    new Envelope(
                        (new CreateOrder($order->getId(), $order->getChannel()->getCode()))
                    )
                );
                break;
            case OrderTransitions::TRANSITION_CANCEL:
                $order->getOmnisendOrderDetails()->setCancelledAt(new DateTime());
                $this->orderRepository->add($order);

                $this->messageBus->dispatch(
                    new Envelope(
                        new CancelOrder($order->getId(), $order->getChannel()->getCode())
                    )
                );
                break;
            case OrderTransitions::TRANSITION_FULFILL:
                $this->messageBus->dispatch(
                    new Envelope(
                        (new UpdateOrderState($order->getId(), $order->getChannel()->getCode()))
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

        if ($order->getId() !== null) {
            $this->messageBus->dispatch(
                new Envelope(
                    (new UpdateOrderState($order->getId(), $order->getChannel()->getCode()))
                )
            );
        }
    }

    public function onShipmentStateChange(ShipmentInterface $shipment): void
    {
        /** @var OrderInterface $order */
        $order = $shipment->getOrder();

        if ($order->getId() !== null) {
            $this->messageBus->dispatch(
                new Envelope(
                    (new UpdateOrderState($order->getId(), $order->getChannel()->getCode()))
                )
            );
        }
    }
}
