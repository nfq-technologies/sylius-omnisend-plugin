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

namespace Tests\NFQ\SyliusOmnisendPlugin\Builder\Request;

use NFQ\SyliusOmnisendPlugin\Builder\Request\OrderBuilder;
use NFQ\SyliusOmnisendPlugin\Builder\Request\OrderBuilderInterface;
use NFQ\SyliusOmnisendPlugin\Factory\Request\OrderAddressFactoryInterface;
use NFQ\SyliusOmnisendPlugin\Factory\Request\OrderProductFactoryInterface;
use NFQ\SyliusOmnisendPlugin\Mapper\OrderPaymentStateMapper;
use NFQ\SyliusOmnisendPlugin\Mapper\OrderStateMapper;
use NFQ\SyliusOmnisendPlugin\Resolver\DefaultOrderCourierDataResolver;
use NFQ\SyliusOmnisendPlugin\Resolver\OrderAdditionalDataResolverInterface;
use NFQ\SyliusOmnisendPlugin\Resolver\OrderCouponResolverInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\AdjustmentInterface;
use Sylius\Component\Core\Model\Customer;
use Sylius\Component\Core\Model\Order;
use Sylius\Component\Core\Model\OrderItem;
use Sylius\Component\Core\Model\OrderItemUnit;
use Sylius\Component\Core\Model\Payment;
use Sylius\Component\Core\Model\PaymentMethod;
use Sylius\Component\Core\Model\Shipment;
use Sylius\Component\Core\Model\ShippingMethod;
use Sylius\Component\Order\Model\Adjustment;
use Sylius\Component\Payment\Model\PaymentMethodTranslation;
use Sylius\Component\Shipping\Model\ShippingMethodTranslation;
use Symfony\Component\Routing\RouterInterface;
use Tests\NFQ\SyliusOmnisendPlugin\Mock\OrderMock;

class OrderBuilderTest extends TestCase
{
    private OrderBuilderInterface $builder;

    /** @var OrderAddressFactoryInterface & MockObject */
    private OrderAddressFactoryInterface $addressFactory;

    /** @var OrderProductFactoryInterface & MockObject */
    private OrderProductFactoryInterface $orderProductFactory;

    /** @var OrderStateMapper & MockObject */
    private OrderStateMapper $stateMapper;

    /** @var OrderPaymentStateMapper & MockObject */
    private OrderPaymentStateMapper $paymentStateMapper;

    /** @var OrderCouponResolverInterface & MockObject */
    private OrderCouponResolverInterface $orderCouponResolver;

    /** @var RouterInterface & MockObject */
    private RouterInterface $router;

    /** @var OrderAdditionalDataResolverInterface & MockObject */
    private OrderAdditionalDataResolverInterface $orderAdditionalDataResolver;

    protected function setUp(): void
    {
        $this->addressFactory = $this->createMock(OrderAddressFactoryInterface::class);
        $this->orderProductFactory = $this->createMock(OrderProductFactoryInterface::class);
        $this->stateMapper = $this->createMock(OrderStateMapper::class);
        $this->paymentStateMapper = $this->createMock(OrderPaymentStateMapper::class);
        $this->orderCouponResolver = $this->createMock(OrderCouponResolverInterface::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->orderAdditionalDataResolver = $this->createMock(OrderAdditionalDataResolverInterface::class);
        $this->builder = new OrderBuilder(
            $this->addressFactory,
            $this->orderProductFactory,
            $this->stateMapper,
            $this->paymentStateMapper,
            $this->orderCouponResolver,
            new DefaultOrderCourierDataResolver(),
            $this->orderAdditionalDataResolver,
            $this->router
        );
    }

    public function testIfBuildsOrderData(): void
    {
        $order = new Order();
        $order->setLocaleCode('en');
        $order->setNumber('R0011');
        $order->setCreatedAt(new \DateTime('2012-02-12 12:12:12'));
        $payment = new Payment();
        $paymentMethod = new PaymentMethod();
        $paymentMethodTrans = new PaymentMethodTranslation();
        $paymentMethodTrans->setLocale('en');
        $paymentMethodTrans->setName('paymentMethodTrans');
        $paymentMethod->addTranslation($paymentMethodTrans);
        $paymentMethod->setCurrentLocale('en');
        $payment->setMethod($paymentMethod);
        $order->addPayment($payment);

        $shipping = new Shipment();
        $shippingMethod = new ShippingMethod();
        $shippingMethodTrans = new ShippingMethodTranslation();
        $shippingMethodTrans->setName('shippingMethodTrans');
        $shippingMethodTrans->setLocale('en');
        $shippingMethod->addTranslation($shippingMethodTrans);
        $shippingMethod->setCurrentLocale('en');
        $shipping->setMethod($shippingMethod);
        $order->addShipment($shipping);

        $customer = new Customer();
        $customer->setEmail('test@nfq.lt');

        $order->setCustomer($customer);
        $order->setNotes('Notes');
        $order->setCurrencyCode('Notes');

        $this->builder->createOrder();
        $this->builder->addOrderData($order);
        $result = $this->builder->getOrder();

        $this->assertEquals('2012-02-12T12:12:12+00:00', $result->getCreatedAt());
        $this->assertEquals(null, $result->getUpdatedAt());
        $this->assertEquals(11, $result->getOrderNumber());
        $this->assertEquals('test@nfq.lt', $result->getEmail());
        $this->assertEquals('Notes', $result->getContactNote());
        $this->assertEquals('paymentMethodTrans', $result->getPaymentMethod());
        $this->assertEquals('shippingMethodTrans', $result->getShippingMethod());
    }

    public function testIfBuildsOrderTotals(): void
    {
        $order = new OrderMock();
        $order->setCurrencyCode('EUR');
        $order->setLocaleCode('en');
        $orderItem = new OrderItem();
        $orderItem->setOrder($order);
        $orderItem->setUnitPrice(20000);
        $orderItem->addUnit(new OrderItemUnit($orderItem));
        $orderItem->setProductName('Name');
        $adjustment1 = new Adjustment();
        $adjustment1->setAmount(-1000);
        $adjustment1->setType(AdjustmentInterface::ORDER_ITEM_PROMOTION_ADJUSTMENT);
        $orderItem->addAdjustment($adjustment1);
        $adjustment2 = new Adjustment();
        $adjustment2->setAmount(2000);
        $adjustment2->setType(AdjustmentInterface::TAX_ADJUSTMENT);
        $orderItem->addAdjustment($adjustment2);
        $adjustment3 = new Adjustment();
        $adjustment3->setAmount(4000);
        $adjustment3->setType(AdjustmentInterface::ORDER_SHIPPING_PROMOTION_ADJUSTMENT);
        $order->addAdjustment($adjustment3);

        $this->builder->createOrder();
        $this->builder->addTotals($order);
        $result = $this->builder->getOrder();

        $this->assertEquals('EUR', $result->getCurrency());
        $this->assertEquals(25000, $result->getOrderSum());
        $this->assertEquals(20000, $result->getSubTotalSum());
        $this->assertEquals(2000, $result->getTaxSum());
        $this->assertEquals(4000, $result->getShippingSum());
        $this->assertEquals(1000, $result->getDiscountSum());
    }
}
