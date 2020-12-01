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
