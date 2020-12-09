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

namespace NFQ\SyliusOmnisendPlugin\Mapper;

use NFQ\SyliusOmnisendPlugin\Builder\Request\Constants\OrderFulfillmentStatus;
use Sylius\Component\Core\Model\OrderInterface;

class OrderStateMapper
{
    private const DEFAULT_MAP = [
        OrderInterface::STATE_CART => OrderFulfillmentStatus::UNFULFILL,
        OrderInterface::STATE_NEW => OrderFulfillmentStatus::NEW,
        OrderInterface::STATE_CANCELLED => OrderFulfillmentStatus::UNFULFILL,
        OrderInterface::STATE_FULFILLED => OrderFulfillmentStatus::FULFILL,
    ];

    /** @var array */
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
