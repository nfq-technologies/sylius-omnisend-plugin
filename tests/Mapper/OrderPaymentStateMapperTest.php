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

namespace Tests\NFQ\SyliusOmnisendPlugin\Mapper;

use NFQ\SyliusOmnisendPlugin\Builder\Request\Constants\OrderPaymentStatus;
use NFQ\SyliusOmnisendPlugin\Mapper\OrderPaymentStateMapper;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\Order;
use Sylius\Component\Core\OrderPaymentStates;

class OrderPaymentStateMapperTest extends TestCase
{
    public function testIfReturnsCorrectState()
    {
        $testData = [
            OrderPaymentStates::STATE_CART => OrderPaymentStatus::AWAITING,
            OrderPaymentStates::STATE_CANCELLED => OrderPaymentStatus::VOID,
            OrderPaymentStates::STATE_AWAITING_PAYMENT => OrderPaymentStatus::AWAITING,
            OrderPaymentStates::STATE_PARTIALLY_PAID => OrderPaymentStatus::PARTIALLY_PAY,
            OrderPaymentStates::STATE_PAID => OrderPaymentStatus::PAY,
            OrderPaymentStates::STATE_AUTHORIZED => OrderPaymentStatus::AWAITING,
            OrderPaymentStates::STATE_PARTIALLY_AUTHORIZED => OrderPaymentStatus::AWAITING,
            OrderPaymentStates::STATE_PARTIALLY_REFUNDED => OrderPaymentStatus::PARTIALLY_REFUND,
            OrderPaymentStates::STATE_REFUNDED => 'REFUNDED_CHANGED',
            'CUSTOM' => 'CUSTOM',
            '???' => OrderPaymentStatus::AWAITING,
        ];

        $mapper = new OrderPaymentStateMapper(
            [
                OrderPaymentStates::STATE_REFUNDED => 'REFUNDED_CHANGED',
                'CUSTOM' => 'CUSTOM',
            ]
        );

        foreach ($testData as $key => $item) {
            $order = new Order();
            $order->setPaymentState($key);
            $this->assertEquals($mapper->getState($order), $item);
        }
    }
}
