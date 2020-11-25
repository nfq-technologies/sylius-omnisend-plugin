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

namespace Tests\NFQ\SyliusOmnisendPlugin\Message\Handler;

use NFQ\SyliusOmnisendPlugin\Builder\Request\OrderBuilderDirectorInterface;
use NFQ\SyliusOmnisendPlugin\Client\OmnisendClientInterface;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\Order;
use NFQ\SyliusOmnisendPlugin\Client\Response\Model\CartSuccess;
use NFQ\SyliusOmnisendPlugin\Message\Command\CancelOrder;
use NFQ\SyliusOmnisendPlugin\Message\Handler\CancelOrderHandler;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Tests\NFQ\SyliusOmnisendPlugin\Mock\OrderMock;

class CancelOrderHandlerTest extends TestCase
{
    /** @var CancelOrderHandler */
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

        $this->handler = new CancelOrderHandler(
            $this->omnisendClient,
            $this->orderRepository,
            $this->orderBuilderDirector
        );
    }

    public function testIfCancelsOrders()
    {
        $order = new OrderMock();
        $order->getOmnisendOrderDetails()->setCartId('444');

        $this->omnisendClient
            ->expects($this->once())
            ->method('patchOrder')
            ->willReturn((new CartSuccess())->setCartID('444'));

        $this->orderRepository
            ->expects($this->once())
            ->method('find')
            ->willReturn($order);

        $this->orderBuilderDirector
            ->expects($this->once())
            ->method('buildUpdatedStatesData')
            ->willReturn(new Order());

        $this->handler->__invoke(
            (new CancelOrder(444, 'en'))
        );
    }
}
