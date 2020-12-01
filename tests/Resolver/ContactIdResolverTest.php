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

namespace Tests\NFQ\SyliusOmnisendPlugin\Resolver;

use NFQ\SyliusOmnisendPlugin\Model\ContactAwareInterface;
use NFQ\SyliusOmnisendPlugin\Resolver\ContactIdResolver;
use NFQ\SyliusOmnisendPlugin\Resolver\ContactIdResolverInterface;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\CustomerInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Tests\NFQ\SyliusOmnisendPlugin\Mock\CustomerMock;
use Tests\NFQ\SyliusOmnisendPlugin\Mock\OrderMock;

class ContactIdResolverTest extends TestCase
{
    /** @var ContactIdResolverInterface */
    private $resolver;

    /** @var Request */
    private $request;

    protected function setUp(): void
    {
        $requestStack = $this->createMock(RequestStack::class);
        $this->request = $this->createMock(Request::class);
        $requestStack
            ->expects($this->once())
            ->method('getCurrentRequest')
            ->willReturn($this->request);

        $this->resolver = new ContactIdResolver($requestStack);
    }

    public function testIfReturnsNullIfNothingIsSet()
    {
        $order = new OrderMock();
        $this->request->cookies = $this->createMock(ParameterBag::class);
        $this->request->cookies
            ->expects($this->once())
            ->method('has')
            ->willReturn(false);
        $this->assertNull($this->resolver->resolve($order));
    }

    public function testIfReturnsOrderData()
    {
        $this->request->cookies = $this->createMock(ParameterBag::class);
        $this->request->cookies
            ->expects($this->never())
            ->method('has')
            ->willReturn(false);
        $order = new OrderMock();
        /** @var CustomerInterface&ContactAwareInterface $customer */
        $customer = new CustomerMock();
        $customer->setOmnisendContactId('1111');
        $order->setCustomer($customer);
        $this->assertEquals('1111', $this->resolver->resolve($order));
    }

    public function testIfReturnsCookiesData()
    {
        $order = new OrderMock();
        $this->request->cookies = $this->createMock(ParameterBag::class);
        $this->request->cookies
            ->expects($this->once())
            ->method('has')
            ->willReturn(true);

        $this->request->cookies
            ->expects($this->once())
            ->method('get')
            ->willReturn('3');

        $this->assertEquals('3', $this->resolver->resolve($order));
    }
}
