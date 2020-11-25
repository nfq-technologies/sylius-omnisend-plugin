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

interface OrderCourierDataResolverInterface
{
    public function getCourierUrl(OrderInterface $order): ?string;

    public function getCourierTitle(OrderInterface $order): ?string;
}