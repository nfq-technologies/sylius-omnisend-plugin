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

namespace Tests\NFQ\SyliusOmnisendPlugin\Builder\Request;

use NFQ\SyliusOmnisendPlugin\Builder\Request\CartBuilder;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\CartProduct;
use NFQ\SyliusOmnisendPlugin\Exception\UserCannotBeIdentifiedException;
use NFQ\SyliusOmnisendPlugin\Factory\Request\CartProductFactory;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\Customer;
use Sylius\Component\Core\Model\OrderItem;
use Sylius\Component\Core\Model\OrderItemUnit;
use Sylius\Component\Resource\Generator\RandomnessGeneratorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Tests\NFQ\SyliusOmnisendPlugin\Mock\OrderMock;

class CartBuilderTest extends TestCase
{
    /** @var CartBuilder */
    private $builder;

    /** @var RouterInterface */
    private $router;

    /** @var Request */
    private $request;

    /** @var CartProductFactory */
    private $productFactory;

    /** @var RandomnessGeneratorInterface */
    private $generator;

    protected function setUp(): void
    {
        $this->request = $this->createMock(Request::class);
        $requestStack = $this->createMock(RequestStack::class);
        $requestStack
            ->expects($this->once())
            ->method('getCurrentRequest')
            ->willReturn($this->request);
        $this->router = $this->createMock(RouterInterface::class);
        $this->generator = $this->createMock(RandomnessGeneratorInterface::class);
        $this->productFactory = $this->createMock(CartProductFactory::class);
        $this->builder = new CartBuilder(
            $this->router,
            $requestStack,
            $this->productFactory,
            $this->generator
        );
    }

    public function testIfThrowsException()
    {
        $this->expectException(UserCannotBeIdentifiedException::class);
        $this->builder->createCart();
        $this->builder->addCustomerData(new OrderMock());
    }

    public function testIfSetsContactId()
    {
        $this->builder->createCart();
        $this->builder->addCustomerData(new OrderMock(), '111');
        $result = $this->builder->getCart();

        $this->assertEquals($result->getContactID(), '111');
    }

    public function testIfSetsCustomerData()
    {
        $order = new OrderMock();
        $orderCustomer = new Customer();
        $orderCustomer->setEmail('test@nfq.lt');
        $orderCustomer->setPhoneNumber('4444');
        $order->setCustomer($orderCustomer);
        $this->builder->createCart();
        $this->builder->addCustomerData($order, '111');
        $result = $this->builder->getCart();

        $this->assertEquals($result->getContactID(), '111');
        $this->assertEquals($result->getEmail(), 'test@nfq.lt');
        $this->assertEquals($result->getPhone(), '4444');
    }

    public function testIfSetsCorrectRecoveryUrl()
    {
        $this->router
            ->expects($this->once())
            ->method('generate')
            ->willReturnCallback(
                function ($route, $config, $type) {
                    $this->assertEquals($route, 'nfq_sylius_omnisend_recover_action');
                    $this->assertEquals($config, [
                        'cartId' => '4444',
                        '_locale' => 'en',
                    ]);
                    $this->assertEquals($type, UrlGeneratorInterface::ABSOLUTE_URL);

                    return 'url';
                }
            );
        $order = new OrderMock();
        $order->setLocaleCode('en');
        $order->getOmnisendOrderDetails()->setCartId('4444');
        $this->builder->createCart();
        $this->builder->addRecoveryUrl($order);
        $this->builder->getCart();
    }

    public function testIfAddAllProducts()
    {
        $order = new OrderMock();
        $item2 = new OrderItem();
        $item2->setUnitPrice(5000);
        $item2->addUnit(new OrderItemUnit($item2));
        $order->addItem($item2);
        $item3 = new OrderItem();
        $item3->setUnitPrice(5000);
        $item3->addUnit(new OrderItemUnit($item3));
        $order->addItem($item3);

        $this->productFactory
            ->expects($this->exactly(2))
            ->method('create')
            ->willReturn(new CartProduct());
        $this->builder->createCart();
        $this->builder->addProducts($order);
        $this->builder->getCart();
    }

    public function testIfSetsAllOrderData()
    {
        $order = new OrderMock();
        $item2 = new OrderItem();
        $item2->setUnitPrice(5000);
        $item2->addUnit(new OrderItemUnit($item2));
        $order->addItem($item2);
        $order->setCurrencyCode('EUR');
        $this->builder->createCart();
        $this->builder->addOrderData($order);
        $result = $this->builder->getCart();

        $this->assertNotNull($result->getCartID());
        $this->assertEquals('EUR', $result->getCurrency());
        $this->assertEquals(5000, $result->getCartSum());
    }
}
