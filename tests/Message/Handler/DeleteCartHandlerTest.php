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

namespace Tests\NFQ\SyliusOmnisendPlugin\Message\Handler;

use NFQ\SyliusOmnisendPlugin\Client\OmnisendClientInterface;
use NFQ\SyliusOmnisendPlugin\Client\Response\Model\CartSuccess;
use NFQ\SyliusOmnisendPlugin\Message\Command\DeleteCart;
use NFQ\SyliusOmnisendPlugin\Message\Handler\DeleteCartHandler;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Tests\NFQ\SyliusOmnisendPlugin\Mock\OrderMock;

class DeleteCartHandlerTest extends TestCase
{
    /** @var DeleteCartHandler */
    private $handler;

    /** @var OmnisendClientInterface */
    private $omnisendClient;

    /** @var OrderRepositoryInterface */
    private $orderRepository;

    protected function setUp(): void
    {
        $this->omnisendClient = $this->createMock(OmnisendClientInterface::class);
        $this->orderRepository = $this->createMock(OrderRepositoryInterface::class);

        $this->handler = new DeleteCartHandler(
            $this->omnisendClient,
            $this->orderRepository
        );
    }

    public function testIfDeletesIfOrderExists()
    {
        $order = new OrderMock();
        $order->getOmnisendOrderDetails()->setCartId('444');

        $this->omnisendClient
            ->expects($this->once())
            ->method('deleteCart')
            ->willReturn((new CartSuccess())->setCartID('444'));

        $this->orderRepository
            ->expects($this->once())
            ->method('find')
            ->willReturn($order);

        $this->handler->__invoke(
            new DeleteCart(1, 'a', 'channel')
        );

        $this->assertEquals($order->getOmnisendOrderDetails()->getCartId(), null);
    }
}
