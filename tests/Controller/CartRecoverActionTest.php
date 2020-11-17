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

namespace Tests\NFQ\SyliusOmnisendPlugin\Controller;

use NFQ\SyliusOmnisendPlugin\Controller\CartRecoverAction;
use NFQ\SyliusOmnisendPlugin\Factory\Request\BatchFactory;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\CoreBundle\Storage\CartSessionStorage;
use Sylius\Component\Core\Model\Channel;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Sylius\Component\Core\Storage\CartStorageInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Tests\NFQ\SyliusOmnisendPlugin\Mock\OrderMock;

class CartRecoverActionTest extends TestCase
{
    /** @var CartRecoverAction */
    private $controller;

    /** @var CartStorageInterface */
    private $sessionStorage;

    /** @var RouterInterface */
    private $router;

    /** @var OrderRepositoryInterface */
    private $orderRepository;

    protected function setUp(): void
    {
        $this->sessionStorage = $this->createMock(CartStorageInterface::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->orderRepository = $this->createMock(OrderRepositoryInterface::class);

        $this->controller = new CartRecoverAction(
            $this->sessionStorage,
            $this->router,
            $this->orderRepository,
        );
    }

    public function testIfRedirectsCorrectly(): void
    {
        $redirect = $this->controller->__invoke(new Request());
        $this->assertInstanceOf(RedirectResponse::class, $redirect);
        $this->assertEquals('sylius_shop_homepage', $redirect->getTargetUrl());
    }

    public function testIfRedirectsCorrectlyIfIdExists(): void
    {
        $this->orderRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->willReturn(null);
        $request = $this->createMock(Request::class);
        $request
            ->expects($this->once())
            ->method('get')
            ->willReturn('111111');
        $redirect = $this->controller->__invoke($request);
        $this->assertEquals('sylius_shop_homepage', $redirect->getTargetUrl());
    }

    public function testIfRedirectsCorrectlyIfOrderExists(): void
    {
        $order = new OrderMock();
        $channel = new Channel();
        $channel->setCode('a');
        $order->setChannel($channel);
        $order->setOmnisendCartId('111');
        $order->setTokenValue('TOKEN');
        $this->orderRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->willReturn($order);
        $this->router
            ->expects($this->once())
            ->method('generate')
            ->willReturn('url');
        $request = new Request(['cartId' => '11111'], ['cartId' => '11111']);
        $redirect = $this->controller->__invoke($request);
        $this->assertEquals('url', $redirect->getTargetUrl());
    }
}
