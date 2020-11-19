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

namespace Tests\NFQ\SyliusOmnisendPlugin\Message\Handler;

use NFQ\SyliusOmnisendPlugin\Builder\Request\CartBuilderDirectorInterface;
use NFQ\SyliusOmnisendPlugin\Client\OmnisendClientInterface;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\Cart;
use NFQ\SyliusOmnisendPlugin\Client\Response\Model\CartSuccess;
use NFQ\SyliusOmnisendPlugin\Message\Command\UpdateCart;
use NFQ\SyliusOmnisendPlugin\Message\Handler\UpdateCartHandler;
use NFQ\SyliusOmnisendPlugin\Model\OrderInterface;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\Channel;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Tests\NFQ\SyliusOmnisendPlugin\Mock\OrderMock;

class UpdateCartHandlerTest extends TestCase
{
    /** @var UpdateCartHandler */
    private $handler;

    /** @var OrderRepositoryInterface */
    private $orderRepository;

    /** @var CartBuilderDirectorInterface */
    private $cartBuilderDirector;

    /** @var OmnisendClientInterface */
    private $omnisendClient;

    protected function setUp(): void
    {
        $this->orderRepository = $this->createMock(OrderRepositoryInterface::class);
        $this->cartBuilderDirector = $this->createMock(CartBuilderDirectorInterface::class);
        $this->omnisendClient = $this->createMock(OmnisendClientInterface::class);

        $this->handler = new UpdateCartHandler(
            $this->orderRepository,
            $this->cartBuilderDirector,
            $this->omnisendClient
        );
    }

    public function testIfDoesNotApplyAnyActionIfOrderDoesNotExists()
    {
        $this->orderRepository
            ->expects($this->once())
            ->method('find')
            ->willReturn(null);

        $this->omnisendClient
            ->expects($this->never())
            ->method('postCart');

        $this->handler->__invoke((new UpdateCart())->setOrderId(1)->setContactId('55')->setChannelCode('a'));
    }

    public function testIfCallCreateActionIfDoesNotExits()
    {
        $order = new OrderMock();
        $channel = new Channel();
        $channel->setCode('a');
        $order->setChannel($channel);
        $this->orderRepository
            ->expects($this->once())
            ->method('find')
            ->willReturn($order);

        $this->omnisendClient
            ->expects($this->once())
            ->method('postCart')
            ->willReturn((new CartSuccess())->setCartID('444'));

        $this->cartBuilderDirector
            ->expects($this->once())
            ->method('build')
            ->willReturn(new Cart());

        $this->omnisendClient
            ->expects($this->never())
            ->method('patchCart');

        $this->orderRepository
            ->expects($this->once())
            ->method('add')
            ->willReturnCallback(function (OrderInterface $order) {
                $this->assertEquals($order->getOmnisendOrderDetails()->getCartId(), '444');
            });

        $this->handler->__invoke(
            (new UpdateCart())
                ->setOrderId(1)
                ->setContactId('55')
                ->setChannelCode('a')
        );
    }

    public function testIfCallPatchActionIfCartExists()
    {
        $order = new OrderMock();
        $channel = new Channel();
        $channel->setCode('a');
        $order->setChannel($channel);
        $order->getOmnisendOrderDetails()->getCartId()('2222');
        $this->orderRepository
            ->expects($this->once())
            ->method('find')
            ->willReturn($order);

        $this->orderRepository
            ->expects($this->never())
            ->method('add');

        $this->omnisendClient
            ->expects($this->once())
            ->method('patchCart')
            ->willReturn((new CartSuccess())->setCartID('444'));

        $this->cartBuilderDirector
            ->expects($this->once())
            ->method('build')
            ->willReturn(new Cart());

        $this->omnisendClient
            ->expects($this->never())
            ->method('postCart');

        $this->handler->__invoke(
            (new UpdateCart())
                ->setOrderId(1)
                ->setContactId('55')
                ->setChannelCode('a')
        );
    }
}
