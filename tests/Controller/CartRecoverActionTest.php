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

namespace Tests\NFQ\SyliusOmnisendPlugin\Controller;

use NFQ\SyliusOmnisendPlugin\Controller\CartRecoverAction;
use NFQ\SyliusOmnisendPlugin\Model\OrderDetails;
use NFQ\SyliusOmnisendPlugin\Setter\ContactCookieSetter;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\Channel;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Sylius\Component\Core\Storage\CartStorageInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
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

    /** @var RepositoryInterface */
    private $orderRepository;

    /** @var ContactCookieSetter */
    private $contactCookieSetter;

    protected function setUp(): void
    {
        $this->sessionStorage = $this->createMock(CartStorageInterface::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->orderRepository = $this->createMock(RepositoryInterface::class);
        $this->contactCookieSetter = $this->createMock(ContactCookieSetter::class);

        $this->controller = new CartRecoverAction(
            $this->sessionStorage,
            $this->router,
            $this->orderRepository,
            $this->contactCookieSetter,
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
            ->expects($this->any())
            ->method('get')
            ->willReturn('111111');
        $redirect = $this->controller->__invoke($request);
        $this->assertEquals('sylius_shop_homepage', $redirect->getTargetUrl());
    }

    public function testIfRedirectsCorrectlyIfOrderExists(): void
    {
        $order = new OrderMock();
        $details = new OrderDetails();
        $channel = new Channel();
        $channel->setCode('a');
        $order->setChannel($channel);
        $order->getOmnisendOrderDetails()->setCartId('111');
        $order->setTokenValue('TOKEN');
        $details->setOrder($order);
        $this->orderRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->willReturn($details);
        $this->router
            ->expects($this->once())
            ->method('generate')
            ->willReturn('url');
        $request = new Request(['cartId' => '11111', 'omnisendContactID' => '444'], ['cartId' => '11111', 'omnisendContactID' => '444']);
        $this->contactCookieSetter
            ->expects($this->once())
            ->method('set');
        $redirect = $this->controller->__invoke($request);
        $this->assertEquals('url', $redirect->getTargetUrl());
    }
}
