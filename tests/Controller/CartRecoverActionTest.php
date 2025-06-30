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
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\Channel;
use Sylius\Component\Core\Storage\CartStorageInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Tests\NFQ\SyliusOmnisendPlugin\Mock\OrderMock;

class CartRecoverActionTest extends TestCase
{
    private const SYLIUS_HOME_URL = 'https://sylius.com';
    private const SYLIUS_CART_URL = 'https://sylius.com/cart';
    private const SYLIUS_HOMEPAGE_ROUTE = 'sylius_shop_homepage';

    private CartRecoverAction $controller;

    /** @var CartStorageInterface & MockObject */
    private $sessionStorage;

    /** @var RouterInterface & MockObject */
    private $router;

    /** @var RepositoryInterface & MockObject */
    private $orderRepository;

    /** @var ContactCookieSetter & MockObject */
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
        $this->givenRouterGeneratesSyliusHomePageUrl();
        $redirect = $this->controller->__invoke(new Request());
        $this->assertEquals(self::SYLIUS_HOME_URL, $redirect->getTargetUrl());
    }

    private function givenRouterGeneratesSyliusHomePageUrl(): void
    {
        $this->router->method('generate')
            ->with(self::SYLIUS_HOMEPAGE_ROUTE)
            ->willReturn(self::SYLIUS_HOME_URL);
    }

    public function testIfRedirectsCorrectlyIfIdExists(): void
    {
        $this->orderRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->willReturn(null);
        $request = $this->createMock(Request::class);
        $request
            ->method('get')
            ->willReturn('111111');
        $this->givenRouterGeneratesSyliusHomePageUrl();
        $redirect = $this->controller->__invoke($request);
        $this->assertEquals(self::SYLIUS_HOME_URL, $redirect->getTargetUrl());
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
            ->with('sylius_shop_cart_summary')
            ->willReturn(self::SYLIUS_CART_URL);
        $request = new Request(['cartId' => '11111', 'omnisendContactID' => '444'],
            ['cartId' => '11111', 'omnisendContactID' => '444']);
        $this->contactCookieSetter
            ->expects($this->once())
            ->method('set');
        $redirect = $this->controller->__invoke($request);
        $this->assertEquals(self::SYLIUS_CART_URL, $redirect->getTargetUrl());
    }
}
