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

namespace NFQ\SyliusOmnisendPlugin\Builder\Request;

use NFQ\SyliusOmnisendPlugin\Client\Request\Model\Cart;
use NFQ\SyliusOmnisendPlugin\Exception\UserCannotBeIdentifiedException;
use NFQ\SyliusOmnisendPlugin\Factory\Request\CartProductFactory;
use NFQ\SyliusOmnisendPlugin\Model\OrderInterface;
use NFQ\SyliusOmnisendPlugin\Utils\DatetimeHelper;
use Sylius\Resource\Generator\RandomnessGeneratorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class CartBuilder implements CartBuilderInterface
{
    /** @var string */
    private const CART_ROUTE_NAME = 'nfq_sylius_omnisend_recover_action';

    /** @var Cart */
    private $cart;

    /** @var RouterInterface */
    private $router;

    /** @var Request|null */
    private $request;

    /** @var CartProductFactory */
    private $productFactory;

    /** @var RandomnessGeneratorInterface */
    private $generator;

    public function __construct(
        RouterInterface $router,
        RequestStack $requestStack,
        CartProductFactory $productFactory,
        RandomnessGeneratorInterface $generator
    ) {
        $this->generator = $generator;
        $this->router = $router;
        $this->request = $requestStack->getCurrentRequest();
        $this->productFactory = $productFactory;
    }

    public function createCart(): void
    {
        $this->cart = new Cart();
    }

    public function addOrderData(OrderInterface $order): void
    {
        if (null === $order->getOmnisendOrderDetails()->getCartId()) {
            $order->getOmnisendOrderDetails()->setCartId($this->generator->generateNumeric(32));
        }
        $this->cart->setCartID($order->getOmnisendOrderDetails()->getCartId());
        $this->cart->setCurrency($order->getCurrencyCode());
        $this->cart->setCartSum($order->getTotal());
        $this->cart->setCreatedAt(DatetimeHelper::format($order->getCreatedAt()));
        $this->cart->setUpdatedAt(null !== $order->getUpdatedAt() ? DatetimeHelper::format($order->getUpdatedAt()) : null);
    }

    public function addCustomerData(OrderInterface $order, ?string $contactId = null): void
    {
        $customer = $order->getCustomer();
        $this->cart->setContactID($contactId);

        if (null !== $customer) {
            $this->cart->setEmail($customer->getEmail());
            $this->cart->setPhone($customer->getPhoneNumber());
        }

        if ($this->cart->getContactID() === null && $this->cart->getEmail() === null) {
            throw new UserCannotBeIdentifiedException();
        }
    }

    public function addRecoveryUrl(OrderInterface $order): void
    {
        $this->cart->setCartRecoveryUrl(
            $this->router->generate(
                self::CART_ROUTE_NAME,
                [
                    'cartId' => $order->getOmnisendOrderDetails()->getCartId(),
                    '_locale' => $order->getLocaleCode(),
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            )
        );
    }

    public function addProducts(OrderInterface $order): void
    {
        foreach ($order->getItems() as $item) {
            $this->cart->addProduct($this->productFactory->create($item));
        }
    }

    public function getCart(): Cart
    {
        return $this->cart;
    }
}
