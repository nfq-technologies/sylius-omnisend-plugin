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

namespace Tests\NFQ\SyliusOmnisendPlugin\Resolver;

use NFQ\SyliusOmnisendPlugin\Client\Request\Model\OrderCoupon;
use NFQ\SyliusOmnisendPlugin\Resolver\OrderCouponResolverContext;
use NFQ\SyliusOmnisendPlugin\Resolver\OrderCouponResolverInterface;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\Order;

class OrderCouponResolverContextTest extends TestCase
{
    public function testIfResolvesWell()
    {
        $resolver1 = $this->createMock(OrderCouponResolverInterface::class);
        $resolver2 = $this->createMock(OrderCouponResolverInterface::class);
        $resolver3 = $this->createMock(OrderCouponResolverInterface::class);

        $resolver1
            ->expects($this->once())
            ->method('resolve')
            ->willReturn(null);
        $resolver2
            ->expects($this->once())
            ->method('resolve')
            ->willReturn(new OrderCoupon('code', 'test', 10));
        $resolver3
            ->expects($this->never())
            ->method('resolve')
            ->willReturn(null);

        $resolver = new OrderCouponResolverContext([$resolver1, $resolver2, $resolver3]);
        $results = $resolver->resolve(new Order());
        $this->assertEquals($results->getValue(), 10);
        $this->assertEquals($results->getType(), 'test');
        $this->assertEquals($results->getCode(), 'code');
    }

    public function testIfReturnsNull()
    {
        $resolver1 = $this->createMock(OrderCouponResolverInterface::class);

        $resolver1
            ->expects($this->once())
            ->method('resolve')
            ->willReturn(null);

        $resolver = new OrderCouponResolverContext([$resolver1]);
        $results = $resolver->resolve(new Order());
        $this->assertNull($results);
    }
}
