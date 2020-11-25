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

use NFQ\SyliusOmnisendPlugin\Builder\Request\CartBuilderDirector;
use NFQ\SyliusOmnisendPlugin\Builder\Request\CartBuilderInterface;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\Cart;
use NFQ\SyliusOmnisendPlugin\Model\OrderInterface;
use PHPUnit\Framework\TestCase;

class CartBuilderDirectorTest extends TestCase
{
    /** @var CartBuilderDirector */
    private $director;

    /** @var CartBuilderInterface */
    private $builder;

    protected function setUp(): void
    {
        $this->builder = $this->createMock(CartBuilderInterface::class);
        $this->director = new CartBuilderDirector($this->builder);
    }

    public function testIfBuildsWell()
    {
        $this->builder->expects($this->once())->method('createCart');
        $this->builder->expects($this->once())->method('addOrderData');
        $this->builder->expects($this->once())->method('addCustomerData');
        $this->builder->expects($this->once())->method('addRecoveryUrl');
        $this->builder->expects($this->once())->method('addProducts');
        $this->builder
            ->expects($this->once())
            ->method('getCart')
            ->willReturn(new Cart());

        $result = $this->director->build($this->createMock(OrderInterface::class), '33');

        $this->assertInstanceOf(Cart::class, $result);
    }
}
