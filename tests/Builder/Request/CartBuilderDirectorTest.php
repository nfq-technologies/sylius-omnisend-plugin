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
        $this->builder->expects($this->once())->method('addRequestData');
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
