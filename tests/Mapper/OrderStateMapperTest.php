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

use NFQ\SyliusOmnisendPlugin\Builder\Request\Constants\OrderFulfillmentStatus;
use NFQ\SyliusOmnisendPlugin\Mapper\OrderStateMapper;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\Order;
use Sylius\Component\Core\Model\OrderInterface;

class OrderStateMapperTest extends TestCase
{
    public function testIfReturnsCorrectState()
    {
        $testData = [
            OrderInterface::STATE_CART => OrderFulfillmentStatus::UNFULFILL,
            OrderInterface::STATE_NEW => OrderFulfillmentStatus::NEW,
            OrderInterface::STATE_CANCELLED => 'AAAA',
            OrderInterface::STATE_FULFILLED => OrderFulfillmentStatus::FULFILL,
            'CUSTOM' => 'CUSTOM',
            '???' => OrderFulfillmentStatus::NEW,
        ];

        $mapper = new OrderStateMapper(
            [
                OrderInterface::STATE_CANCELLED => 'AAAA',
                'CUSTOM' => 'CUSTOM',
            ]
        );

        foreach ($testData as $key => $item) {
            $order = new Order();
            $order->setState($key);
            $this->assertEquals($mapper->getState($order), $item);
        }
    }
}
