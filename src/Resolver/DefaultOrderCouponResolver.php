<?php

/*
 * This file is part of the NFQ package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
                    $coupon->getCode(),
                    self::DEFAULT_TYPE,
                    $total
                );
            }
        }

        return null;
    }
}
