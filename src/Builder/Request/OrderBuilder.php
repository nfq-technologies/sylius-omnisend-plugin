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
use NFQ\SyliusOmnisendPlugin\Factory\Request\OrderAddressFactoryInterface;
use NFQ\SyliusOmnisendPlugin\Factory\Request\OrderProductFactoryInterface;
use NFQ\SyliusOmnisendPlugin\Mapper\OrderPaymentStateMapper;
use NFQ\SyliusOmnisendPlugin\Mapper\OrderStateMapper;
use NFQ\SyliusOmnisendPlugin\Resolver\OrderCouponResolverInterface;
use NFQ\SyliusOmnisendPlugin\Utils\DatetimeHelper;
use NFQ\SyliusOmnisendPlugin\Utils\Order\OrderNumberResolver;
use Sylius\Bundle\ShopBundle\Calculator\OrderItemsSubtotalCalculatorInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\ShipmentInterface;

class OrderBuilder implements OrderBuilderInterface
{
    /** @var Order */
    private $order;

    /** @var OrderAddressFactoryInterface */
    private $addressFactory;

    /** @var OrderProductFactoryInterface */
    private $orderProductFactory;

    /** @var OrderItemsSubtotalCalculatorInterface */
    private $subtotalCalculator;

    /** @var OrderStateMapper */
    private $stateMapper;

    /** @var OrderPaymentStateMapper */
    private $paymentStateMapper;

    /** @var OrderCouponResolverInterface */
    private $orderCouponResolver;

    public function __construct(
        OrderAddressFactoryInterface $addressFactory,
        OrderProductFactoryInterface $orderProductFactory,
        OrderItemsSubtotalCalculatorInterface $subtotalCalculator,
        OrderStateMapper $orderStateMapper,
        OrderPaymentStateMapper $orderPaymentStateMapper,
        OrderCouponResolverInterface $orderCouponResolver
    ) {
        $this->addressFactory = $addressFactory;
        $this->orderProductFactory = $orderProductFactory;
        $this->subtotalCalculator = $subtotalCalculator;
        $this->stateMapper = $orderStateMapper;
        $this->paymentStateMapper = $orderPaymentStateMapper;
        $this->orderCouponResolver = $orderCouponResolver;
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
        $this->order->setTrackingCode($order->getShipments()->last()->getTracking());
    }

    /** @var \NFQ\SyliusOmnisendPlugin\Model\OrderInterface $order */
    public function addCartData(OrderInterface $order): void
    {
        $this->order->setCartID($order->getOmnisendOrderDetails()->getCartId());
        $this->order->setOrderID($order->getOmnisendOrderDetails()->getCartId());
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

    /** @var \NFQ\SyliusOmnisendPlugin\Model\OrderInterface $order */
    public function addOrderData(OrderInterface $order): void
    {
        /** @var ShipmentInterface $shippingMethod */
        $shipping = $order->getShipments()->last();
        /** @var PaymentInterface $paymentMethod */
        $payment = $order->getLastPayment();

        $this->order->setOrderNumber(OrderNumberResolver::resolve($order->getNumber()));
        $this->order->setEmail($order->getCustomer()->getEmail());
        if (null !== $shipping && null !== $shipping->getMethod()) {
            $this->order->setShippingMethod($shipping->getMethod()->getTranslation($order->getLocaleCode())->getName());
        }
        if (null !== $payment && null !== $payment->getMethod()) {
            $this->order->setPaymentMethod($payment->getMethod()->getTranslation($order->getLocaleCode())->getName());
        }
        $this->order->setContactNote($order->getNotes());
        $this->order->setCreatedAt(DatetimeHelper::format($order->getCreatedAt()));
    }

    public function addTotals(OrderInterface $order): void
    {
        $this->order->setCurrency($order->getCurrencyCode());
        $this->order->setOrderSum($order->getTotal());
        $this->order->setSubTotalSum($this->subtotalCalculator->getSubtotal($order));
        $this->order->setTaxSum($order->getTaxTotal());
        $this->order->setShippingSum($order->getShippingTotal());
        $this->order->setDiscountSum(abs($order->getOrderPromotionTotal()));
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
