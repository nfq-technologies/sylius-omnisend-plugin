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

namespace NFQ\SyliusOmnisendPlugin\Controller;

use NFQ\SyliusOmnisendPlugin\Model\OrderDetails;
use NFQ\SyliusOmnisendPlugin\Setter\ContactCookieSetter;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\Order;
use Sylius\Component\Core\Storage\CartStorageInterface;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class CartRecoverAction
{
    /** @var CartStorageInterface */
    private $cartStorage;

    /** @var RouterInterface */
    private $router;

    /** @var RepositoryInterface */
    private $repository;

    /** @var ContactCookieSetter */
    private $contactCookieSetter;

    public function __construct(
        CartStorageInterface $sessionStorage,
        RouterInterface $router,
        RepositoryInterface $orderRepository,
        ContactCookieSetter $contactCookieSetter
    ) {
        $this->cartStorage = $sessionStorage;
        $this->router = $router;
        $this->repository = $orderRepository;
        $this->contactCookieSetter = $contactCookieSetter;
    }

    public function __invoke(Request $request): RedirectResponse
    {
        $cartId = $request->get('cartId');

        if (null === $cartId) {
            return new RedirectResponse($this->router->generate('sylius_shop_homepage'));
        }

        if (null !== $request->get(ContactCookieSetter::COOKIE_NAME)) {
            $this->contactCookieSetter->set($request->get(ContactCookieSetter::COOKIE_NAME));
        }

        /** @var OrderDetails|null $details */
        $details = $this->repository->findOneBy(['cartId' => $cartId]);

        if (null === $details) {
            return new RedirectResponse($this->router->generate('sylius_shop_homepage'));
        }
        /** @var Order $cart */
        $cart = $details->getOrder();
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
