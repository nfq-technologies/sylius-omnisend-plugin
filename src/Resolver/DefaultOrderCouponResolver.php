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

namespace NFQ\SyliusOmnisendPlugin\Resolver;

use NFQ\SyliusOmnisendPlugin\Client\Request\Model\OrderCoupon;
use Sylius\Component\Core\Model\OrderInterface;

class DefaultOrderCouponResolver implements OrderCouponResolverInterface
{
    const DEFAULT_TYPE = 'fixedAmount';

    public function resolve(OrderInterface $order): ?OrderCoupon
    {
        $coupon = $order->getPromotionCoupon();
        if (null !== $coupon) {
            $total = 0;
            foreach ($order->getAdjustmentsRecursively() as $adjustment) {
                if ($adjustment->getOriginCode() === $coupon->getCode()) {
                    $total += abs($adjustment->getAmount());
                }
            }

            if ($total > 0) {
                return new OrderCoupon(
                    $order->getPromotionCoupon()->getCode(),
                    self::DEFAULT_TYPE,
                    $total
                );
            }
        }

        return null;
    }
}
