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

namespace Tests\NFQ\SyliusOmnisendPlugin\Builder\Request;

use NFQ\SyliusOmnisendPlugin\Builder\Request\OrderBuilderDirector;
use NFQ\SyliusOmnisendPlugin\Builder\Request\OrderBuilderInterface;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\Order;
use PHPUnit\Framework\TestCase;

class OrderBuilderDirectorTest extends TestCase
{
    /** @var OrderBuilderDirector */
    private $director;

    /** @var OrderBuilderInterface */
    private $builder;

    protected function setUp(): void
    {
        $this->builder = $this->createMock(OrderBuilderInterface::class);
        $this->director = new OrderBuilderDirector($this->builder);
    }

    public function testIfBuilds()
    {
        $order = new \Sylius\Component\Core\Model\Order();
        $this->builder->expects($this->once())->method('createOrder');
        $this->builder->expects($this->once())->method('addCartData');
        $this->builder->expects($this->once())->method('addOrderData');
        $this->builder->expects($this->once())->method('addProducts');
        $this->builder->expects($this->once())->method('addTrackingData');
        $this->builder->expects($this->once())->method('addCancelData');
        $this->builder->expects($this->once())->method('addStates');
        $this->builder->expects($this->once())->method('addAddresses');
        $this->builder->expects($this->once())->method('addCouponData');
        $this->builder->expects($this->once())->method('addUpdateAt');
        $this->builder
            ->expects($this->once())
            ->method('getOrder')
            ->willReturn(new Order());

        $result = $this->director->build($order);

        $this->assertInstanceOf(Order::class, $result);
    }

    public function testIfBuildsStates()
    {
        $order = new \Sylius\Component\Core\Model\Order();
        $this->builder->expects($this->once())->method('createOrder');
        $this->builder->expects($this->once())->method('addCartData');
        $this->builder->expects($this->once())->method('addStates');
        $this->builder->expects($this->once())->method('addTrackingData');
        $this->builder->expects($this->once())->method('addCancelData');
        $this->builder->expects($this->never())->method('addOrderData');
        $this->builder->expects($this->never())->method('addProducts');
        $this->builder->expects($this->never())->method('addAddresses');
        $this->builder->expects($this->never())->method('addCouponData');
        $this->builder->expects($this->never())->method('addUpdateAt');
        $this->builder
            ->expects($this->once())
            ->method('getOrder')
            ->willReturn(new Order());

        $result = $this->director->buildUpdatedStatesData($order);

        $this->assertInstanceOf(Order::class, $result);
    }
}
