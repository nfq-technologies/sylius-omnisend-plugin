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

namespace NFQ\SyliusOmnisendPlugin\Controller;

use Sylius\Bundle\CoreBundle\Storage\CartSessionStorage;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class CartRecoverAction
{
    /** @var CartSessionStorage */
    private $sessionStorage;

    /** @var RouterInterface */
    private $router;

    /** @var OrderRepositoryInterface */
    private $orderRepository;

    public function __construct(
        CartSessionStorage $sessionStorage,
        RouterInterface $router,
        OrderRepositoryInterface $orderRepository
    ) {
        $this->sessionStorage = $sessionStorage;
        $this->router = $router;
        $this->orderRepository = $orderRepository;
    }

    public function __invoke(Request $request): RedirectResponse
    {
        $cartId = $request->get('cartId');
        /** @var OrderInterface $cart */
        $cart = $this->orderRepository->findOneBy(['omnisendCartId' => $cartId]);

        if (null === $cart) {
            return new RedirectResponse('sylius_shop_homepage');
        }

        $this->sessionStorage->setForChannel($cart->getChannel(), $cart);

        return new RedirectResponse(
            $request->headers->get(
                'referer',
                $this->router->generate('sylius_shop_cart_summary')
            )
        );
    }
}
