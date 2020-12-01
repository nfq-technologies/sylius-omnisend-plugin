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

use NFQ\SyliusOmnisendPlugin\Builder\Request\Constants\OrderPaymentStatus;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\OrderPaymentStates;

class OrderPaymentStateMapper
{
    private const DEFAULT_MAP = [
        OrderPaymentStates::STATE_CART => OrderPaymentStatus::AWAITING,
        OrderPaymentStates::STATE_CANCELLED => OrderPaymentStatus::VOID,
        OrderPaymentStates::STATE_AWAITING_PAYMENT => OrderPaymentStatus::AWAITING,
        OrderPaymentStates::STATE_PARTIALLY_PAID => OrderPaymentStatus::PARTIALLY_PAY,
        OrderPaymentStates::STATE_PAID => OrderPaymentStatus::PAY,
        OrderPaymentStates::STATE_AUTHORIZED => OrderPaymentStatus::AWAITING,
        OrderPaymentStates::STATE_PARTIALLY_AUTHORIZED => OrderPaymentStatus::AWAITING,
        OrderPaymentStates::STATE_PARTIALLY_REFUNDED => OrderPaymentStatus::PARTIALLY_REFUND,
        OrderPaymentStates::STATE_REFUNDED => OrderPaymentStatus::REFUND,
    ];

    /** @var array */
    private $configuredStates;

    public function __construct(array $configuredStates = [])
    {
        $this->configuredStates = $configuredStates;
    }

    public function getState(OrderInterface $order): string
    {
        $orderState = $order->getPaymentState();
        $states = array_merge(self::DEFAULT_MAP, $this->configuredStates);
        if (isset($states[$orderState])) {
            return $states[$orderState];
        }

        return OrderPaymentStatus::AWAITING;
    }
}
