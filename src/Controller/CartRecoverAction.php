<?php

declare(strict_types=1);

/*
 * This file is part of the NFQ package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NFQ\SyliusOmnisendPlugin\Controller;

use NFQ\SyliusOmnisendPlugin\Setter\ContactCookieSetter;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Sylius\Component\Core\Storage\CartStorageInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class CartRecoverAction
{
    /** @var CartStorageInterface */
    private $cartStorage;

    /** @var RouterInterface */
    private $router;

    /** @var OrderRepositoryInterface */
    private $orderRepository;

    /** @var ContactCookieSetter */
    private $contactCookieSetter;

    public function __construct(
        CartStorageInterface $sessionStorage,
        RouterInterface $router,
        OrderRepositoryInterface $orderRepository,
        ContactCookieSetter $contactCookieSetter
    ) {
        $this->cartStorage = $sessionStorage;
        $this->router = $router;
        $this->orderRepository = $orderRepository;
        $this->contactCookieSetter = $contactCookieSetter;
    }

    public function __invoke(Request $request): RedirectResponse
    {
        $cartId = $request->get('cartId');

        if (null === $cartId) {
            return new RedirectResponse('sylius_shop_homepage');
        }

        if (null !== $request->get(ContactCookieSetter::COOKIE_NAME)) {
            $this->contactCookieSetter->set($request->get(ContactCookieSetter::COOKIE_NAME));
        }

        /** @var OrderInterface|null $cart */
        $cart = $this->orderRepository->findOneBy(['omnisendCartId' => $cartId]);

        if (null === $cart) {
            return new RedirectResponse('sylius_shop_homepage');
        }
        /** @var ChannelInterface $channel */
        $channel = $cart->getChannel();

        $this->cartStorage->setForChannel($channel, $cart);

        return new RedirectResponse(
            $request->headers->get(
                'referer',
                $this->router->generate('sylius_shop_cart_summary')
            )
        );
    }
}
