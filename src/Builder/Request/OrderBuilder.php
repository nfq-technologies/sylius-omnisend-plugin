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
use NFQ\SyliusOmnisendPlugin\Factory\Request\OrderAddressFactoryInterface;
use NFQ\SyliusOmnisendPlugin\Factory\Request\OrderProductFactoryInterface;
use NFQ\SyliusOmnisendPlugin\Mapper\OrderPaymentStateMapper;
use NFQ\SyliusOmnisendPlugin\Mapper\OrderStateMapper;
use NFQ\SyliusOmnisendPlugin\Model\OrderDetails;
use NFQ\SyliusOmnisendPlugin\Resolver\OrderAdditionalDataResolverInterface;
use NFQ\SyliusOmnisendPlugin\Resolver\OrderCouponResolverInterface;
use NFQ\SyliusOmnisendPlugin\Resolver\OrderCourierDataResolverInterface;
use NFQ\SyliusOmnisendPlugin\Utils\DatetimeHelper;
use NFQ\SyliusOmnisendPlugin\Utils\Order\OrderNumberResolver;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\ShipmentInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class OrderBuilder implements OrderBuilderInterface
{
    /** @var Order */
    private $order;

    /** @var OrderAddressFactoryInterface */
    private $addressFactory;

    /** @var OrderProductFactoryInterface */
    private $orderProductFactory;

    /** @var OrderStateMapper */
    private $stateMapper;

    /** @var OrderPaymentStateMapper */
    private $paymentStateMapper;

    /** @var OrderCouponResolverInterface */
    private $orderCouponResolver;

    /** @var OrderCourierDataResolverInterface */
    private $orderCourierResolver;

    /** @var OrderAdditionalDataResolverInterface */
    private $orderAdditionalDataResolver;

    /** @var RouterInterface */
    private $router;

    public function __construct(
        OrderAddressFactoryInterface $addressFactory,
        OrderProductFactoryInterface $orderProductFactory,
        OrderStateMapper $orderStateMapper,
        OrderPaymentStateMapper $orderPaymentStateMapper,
        OrderCouponResolverInterface $orderCouponResolver,
        OrderCourierDataResolverInterface $orderCourierResolver,
        OrderAdditionalDataResolverInterface $orderAdditionalDataResolver,
        RouterInterface $router
    ) {
        $this->addressFactory = $addressFactory;
        $this->orderProductFactory = $orderProductFactory;
        $this->stateMapper = $orderStateMapper;
        $this->paymentStateMapper = $orderPaymentStateMapper;
        $this->orderCouponResolver = $orderCouponResolver;
        $this->orderCourierResolver = $orderCourierResolver;
        $this->orderAdditionalDataResolver = $orderAdditionalDataResolver;
        $this->router = $router;
    }

    public function createOrder(): void
    {
        $this->order = new Order();
    }

    public function addProducts(OrderInterface $order): void
    {
        $products = [];

        foreach ($order->getItems() as $item) {
            $products[] = $this->orderProductFactory->create($item);
        }

        $this->order->setProducts($products);
    }

    public function addStates(OrderInterface $order): void
    {
        $this->order->setFulfillmentStatus($this->stateMapper->getState($order));
        $this->order->setPaymentStatus($this->paymentStateMapper->getState($order));
    }

    public function addAddresses(OrderInterface $order): void
    {
        $this->order->setShippingAddress(
            $this->addressFactory->create($order->getShippingAddress(), $order->getLocaleCode())
        );
        $this->order->setBillingAddress(
            $this->addressFactory->create($order->getBillingAddress(), $order->getLocaleCode())
        );
    }

    public function addUpdateAt(OrderInterface $order): void
    {
        $this->order->setUpdatedAt($order->getUpdatedAt() !== null ? DatetimeHelper::format($order->getUpdatedAt()) : null);
    }

    public function addTrackingData(OrderInterface $order): void
    {
        /** @var ShipmentInterface|null $shipping */
        $shipping = $order->getShipments()->last();

        if (null !== $shipping) {
            $this->order->setTrackingCode($shipping->getTracking());
        }

        $this->order->setCourierTitle($this->orderCourierResolver->getCourierTitle($order));
        $this->order->setCourierUrl($this->orderCourierResolver->getCourierUrl($order));
    }

    /** @var \NFQ\SyliusOmnisendPlugin\Model\OrderInterface $order */
    public function addCartData(OrderInterface $order): void
    {
        /** @var OrderDetails $details */
        $details = $order->getOmnisendOrderDetails();

        $this->order->setCartID($details->getCartId());
        $this->order->setOrderID($details->getCartId());
    }

    public function addCouponData(OrderInterface $order): void
    {
        $orderCoupon = $this->orderCouponResolver->resolve($order);

        if (null !== $orderCoupon) {
            $this->order->setDiscountCode($orderCoupon->getCode());
            $this->order->setDiscountType($orderCoupon->getType());
            $this->order->setDiscountValue($orderCoupon->getValue());
        }
    }

    /** @var \NFQ\SyliusOmnisendPlugin\Model\OrderInterface */
    public function addOrderData(OrderInterface $order): void
    {
        /** @var ShipmentInterface|null $shipping */
        $shipping = $order->getShipments()->last();
        /** @var PaymentInterface|null $payment */
        $payment = $order->getLastPayment();

        $this->order->setOrderNumber(OrderNumberResolver::resolve($order->getNumber()));
        if (null !== $order->getCustomer()) {
            $this->order->setEmail($order->getCustomer()->getEmail());
        }
        if (null !== $shipping && null !== $shipping->getMethod()) {
            $this->order->setShippingMethod($shipping->getMethod()->getTranslation($order->getLocaleCode())->getName());
        }
        if (null !== $payment && null !== $payment->getMethod()) {
            $this->order->setPaymentMethod($payment->getMethod()->getTranslation($order->getLocaleCode())->getName());
        }
        $this->order->setContactNote($order->getNotes());
        $this->order->setOrderUrl(
            $this->router->generate(
            'sylius_shop_order_show',
            [
                '_locale' => $order->getLocaleCode(),
                'tokenValue' => $order->getTokenValue(),
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
            )
        );
        $this->order->setCreatedAt(DatetimeHelper::format($order->getCreatedAt()));
    }

    public function addTotals(OrderInterface $order): void
    {
        $this->order->setCurrency($order->getCurrencyCode());
        $this->order->setOrderSum($order->getTotal());
        $this->order->setSubTotalSum($this->getOrderSubtotal($order));
        $this->order->setTaxSum($order->getTaxTotal());
        $this->order->setShippingSum($order->getShippingTotal());
        $this->order->setDiscountSum(abs($order->getOrderPromotionTotal()));
    }

    public function addAdditionalData(OrderInterface $order): void
    {
        $this->order->setTags($this->orderAdditionalDataResolver->getTags($order));
    }

    private function getOrderSubtotal(OrderInterface $order): int
    {
        return array_reduce(
            $order->getItems()->toArray(),
            static function (int $subtotal, OrderItemInterface $item): int {
                return $subtotal + $item->getSubtotal();
            },
            0
        );
    }

    public function getOrder(): Order
    {
        return $this->order;
    }

    /** @var \NFQ\SyliusOmnisendPlugin\Model\OrderInterface $order */
    public function addCancelData(OrderInterface $order): void
    {
        $this->order->setCanceledDate(DatetimeHelper::format($order->getOmnisendOrderDetails()->getCancelledAt()));
    }
}
