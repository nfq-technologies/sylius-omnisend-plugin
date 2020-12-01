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

use NFQ\SyliusOmnisendPlugin\Builder\Request\OrderBuilderDirectorInterface;
use NFQ\SyliusOmnisendPlugin\Client\OmnisendClientInterface;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\Order;
use NFQ\SyliusOmnisendPlugin\Client\Response\Model\CartSuccess;
use NFQ\SyliusOmnisendPlugin\Message\Command\UpdateOrder;
use NFQ\SyliusOmnisendPlugin\Message\Handler\UpdateOrderHandler;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Tests\NFQ\SyliusOmnisendPlugin\Mock\OrderMock;

class UpdateOrderHandlerTest extends TestCase
{
    /** @var UpdateOrderHandler */
    private $handler;

    /** @var OmnisendClientInterface */
    private $omnisendClient;

    /** @var OrderRepositoryInterface */
    private $orderRepository;

    /** @var OrderBuilderDirectorInterface */
    private $orderBuilderDirector;

    protected function setUp(): void
    {
        $this->omnisendClient = $this->createMock(OmnisendClientInterface::class);
        $this->orderRepository = $this->createMock(OrderRepositoryInterface::class);
        $this->orderBuilderDirector = $this->createMock(OrderBuilderDirectorInterface::class);

        $this->handler = new UpdateOrderHandler(
            $this->omnisendClient,
            $this->orderRepository,
            $this->orderBuilderDirector
        );
    }

    public function testIfCreatesOrder()
    {
        $order = new OrderMock();
        $order->getOmnisendOrderDetails()->setCartId('444');

        $this->omnisendClient
            ->expects($this->once())
            ->method('putOrder')
            ->willReturn((new CartSuccess())->setCartID('444'));

        $this->orderRepository
            ->expects($this->once())
            ->method('find')
            ->willReturn($order);

        $this->orderBuilderDirector
            ->expects($this->once())
            ->method('build')
            ->willReturn(new Order());

        $this->handler->__invoke(
            (new UpdateOrder(444, 'en'))
        );
    }
}
