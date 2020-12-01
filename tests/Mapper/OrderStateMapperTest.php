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

namespace Tests\NFQ\SyliusOmnisendPlugin\Mapper;

use NFQ\SyliusOmnisendPlugin\Builder\Request\Constants\OrderFulfillmentStatus;
use NFQ\SyliusOmnisendPlugin\Mapper\OrderStateMapper;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\Order;
use Sylius\Component\Core\Model\OrderInterface;

class OrderStateMapperTest extends TestCase
{
    /** @dataProvider data */
    public function testIfReturnsCorrectState(string $fromState, string $toState)
    {
        $mapper = new OrderStateMapper(
            [
                OrderInterface::STATE_CANCELLED => 'AAAA',
                'CUSTOM' => 'CUSTOM',
            ]
        );

        $order = new Order();
        $order->setState($fromState);
        $this->assertEquals($mapper->getState($order), $toState);
    }

    public function data()
    {
        return [
            'state_cart' => [
                OrderInterface::STATE_CART,
                OrderFulfillmentStatus::UNFULFILL
            ],
            'new' => [
                OrderInterface::STATE_NEW,
                OrderFulfillmentStatus::NEW
            ],
            'cancelled' => [
                OrderInterface::STATE_CANCELLED,
                'AAAA'
            ],
            'fulfilled' => [
                OrderInterface::STATE_FULFILLED,
                OrderFulfillmentStatus::FULFILL,
            ],
            'custom' => [
                'CUSTOM',
                'CUSTOM'
            ],
            'custom2 not applied' => [
                '???',
                OrderFulfillmentStatus::NEW
            ]
        ];
    }
}
