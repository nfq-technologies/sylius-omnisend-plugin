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
use NFQ\SyliusOmnisendPlugin\Builder\Request\Constants\OrderPaymentStatus;
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

    public function getState(string $orderState): string
    {
        $states = array_merge(self::DEFAULT_MAP, $this->configuredStates);
        if (isset($states[$orderState])) {
            return $states[$orderState];
        }

        return OrderPaymentStatus::AWAITING;
    }
}
