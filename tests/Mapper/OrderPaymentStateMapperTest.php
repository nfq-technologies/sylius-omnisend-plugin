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
    /** @dataProvider data */
    public function testIfReturnsCorrectState(string $fromState, string $toState)
    {
        $mapper = new OrderPaymentStateMapper(
            [
                OrderPaymentStates::STATE_REFUNDED => 'REFUNDED_CHANGED',
                'CUSTOM' => 'CUSTOM',
            ]
        );

        $order = new Order();
        $order->setPaymentState($fromState);
        $this->assertEquals($mapper->getState($order), $toState);
    }

    public function data()
    {
        return [
            'STATE_CART' => [
                OrderPaymentStates::STATE_CART,
                OrderPaymentStatus::AWAITING
            ],
            'STATE_CANCELLED' => [
                OrderPaymentStates::STATE_CANCELLED,
                OrderPaymentStatus::VOID
            ],
            'STATE_AWAITING_PAYMENT' => [
                OrderPaymentStates::STATE_AWAITING_PAYMENT,
                OrderPaymentStatus::AWAITING
            ],
            'STATE_PARTIALLY_PAID' => [
                OrderPaymentStates::STATE_PARTIALLY_PAID,
                OrderPaymentStatus::PARTIALLY_PAY,
            ],
            'STATE_PAID' => [
                OrderPaymentStates::STATE_PAID,
                OrderPaymentStatus::PAY,
            ],
            'STATE_AUTHORIZED' => [
                OrderPaymentStates::STATE_AUTHORIZED,
                OrderPaymentStatus::AWAITING,
            ],
            'STATE_PARTIALLY_AUTHORIZED' => [
                OrderPaymentStates::STATE_PARTIALLY_AUTHORIZED,
                OrderPaymentStatus::AWAITING,
            ],
            'STATE_PARTIALLY_REFUNDED' => [
                OrderPaymentStates::STATE_PARTIALLY_REFUNDED,
                OrderPaymentStatus::PARTIALLY_REFUND,
            ],
            'STATE_REFUNDED' => [
                OrderPaymentStates::STATE_REFUNDED,
                'REFUNDED_CHANGED',
            ],
            'custom' => [
                'CUSTOM',
                'CUSTOM'
            ],
            'custom2 not applied' => [
                '???',
                OrderPaymentStatus::AWAITING
            ]
        ];
    }
}
