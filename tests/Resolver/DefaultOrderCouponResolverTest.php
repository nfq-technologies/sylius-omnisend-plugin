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

namespace Tests\NFQ\SyliusOmnisendPlugin\Resolver;

use NFQ\SyliusOmnisendPlugin\Client\Request\Model\OrderCoupon;
use NFQ\SyliusOmnisendPlugin\Resolver\DefaultOrderCouponResolver;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\AdjustmentInterface;
use Sylius\Component\Core\Model\Order;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItem;
use Sylius\Component\Core\Model\OrderItemUnit;
use Sylius\Component\Core\Model\PromotionCoupon;
use Sylius\Component\Order\Model\Adjustment;

class DefaultOrderCouponResolverTest extends TestCase
{
    /** @dataProvider data */
    public function testIfResolvesWell(OrderInterface $order, ?OrderCoupon $orderCoupon)
    {
        $resolver = new DefaultOrderCouponResolver();
        $results = $resolver->resolve($order);

        if ($orderCoupon === null) {
            $this->assertNull($results);
        } else {
            $this->assertEquals($results->getValue(), $orderCoupon->getValue());
            $this->assertEquals($results->getType(), $orderCoupon->getType());
            $this->assertEquals($results->getCode(), $orderCoupon->getCode());
        }
    }

    public function data()
    {
        $order = new Order();
        $promotionCoupon = new PromotionCoupon();
        $promotionCoupon->setCode('code');
        $order->setPromotionCoupon($promotionCoupon);
        $orderItem = new OrderItem();
        $orderItem->setOrder($order);
        $orderItem->setUnitPrice(5000);
        $orderItem->addUnit(new OrderItemUnit($orderItem));
        $orderItem->setProductName('Name');
        $adjustment1 = new Adjustment();
        $adjustment1->setAmount(-1000);
        $adjustment1->setOriginCode('code');
        $adjustment1->setType(AdjustmentInterface::ORDER_ITEM_PROMOTION_ADJUSTMENT);
        $orderItem->addAdjustment($adjustment1);
        $adjustment2 = new Adjustment();
        $adjustment2->setAmount(-2000);
        $adjustment2->setType(AdjustmentInterface::ORDER_ITEM_PROMOTION_ADJUSTMENT);
        $orderItem->addAdjustment($adjustment2);
        $order->addItem($orderItem);

        $order2 = new Order();
        $promotionCoupon2 = new PromotionCoupon();
        $promotionCoupon2->setCode('code');
        $order2->setPromotionCoupon($promotionCoupon2);
        $orderItem2 = new OrderItem();
        $orderItem2->setOrder($order);
        $orderItem2->setUnitPrice(5000);
        $orderItem2->addUnit(new OrderItemUnit($orderItem2));
        $orderItem2->setProductName('Name');
        $adjustment3 = new Adjustment();
        $adjustment3->setAmount(-500);
        $adjustment3->setOriginCode('code');
        $adjustment3->setType(AdjustmentInterface::ORDER_ITEM_PROMOTION_ADJUSTMENT);
        $orderItem2->addAdjustment($adjustment3);
        $adjustment4 = new Adjustment();
        $adjustment4->setAmount(-2500);
        $adjustment4->setOriginCode('code');
        $adjustment4->setType(AdjustmentInterface::ORDER_PROMOTION_ADJUSTMENT);
        $order2->addAdjustment($adjustment4);
        $order2->addItem($orderItem2);

        return [
            'no coupon' => [
                new Order(),
                null
            ],
            'one adjustment' => [
                $order,
                new OrderCoupon('code', 'fixedAmount', 1000)
            ],
            'different adjustment' => [
                $order2,
                new OrderCoupon('code', 'fixedAmount', 3000)
            ]
        ];
    }
}
