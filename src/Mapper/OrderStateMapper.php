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

namespace NFQ\SyliusOmnisendPlugin\Mapper;

use NFQ\SyliusOmnisendPlugin\Builder\Request\Constants\OrderFulfillmentStatus;
use Sylius\Component\Core\Model\OrderInterface;

class OrderStateMapper
{
    private const DEFAULT_MAP = [
        OrderInterface::STATE_CART => OrderFulfillmentStatus::UNFULFILL,
        OrderInterface::STATE_NEW => OrderFulfillmentStatus::NEW,
        OrderInterface::STATE_CANCELLED => OrderFulfillmentStatus::UNFULFILL,
        OrderInterface::STATE_FULFILLED => OrderFulfillmentStatus::FULFILL
    ];

    private $configuredStates;

    public function __construct(array $configuredStates = [])
    {
        $this->configuredStates = $configuredStates;
    }

    public function getState(OrderInterface $order): string
    {
        $orderState = $order->getState();
        $states = array_merge(self::DEFAULT_MAP, $this->configuredStates);
        if (isset($states[$orderState])) {
            return $states[$orderState];
        }

        return OrderFulfillmentStatus::NEW;
    }
}
