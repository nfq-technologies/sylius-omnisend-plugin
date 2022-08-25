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

namespace NFQ\SyliusOmnisendPlugin\Message\Handler;

use NFQ\SyliusOmnisendPlugin\Client\OmnisendClientInterface;
use NFQ\SyliusOmnisendPlugin\Message\Command\DeleteCart;
use NFQ\SyliusOmnisendPlugin\Model\OrderInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;

class DeleteCartHandler
{
    /** @var OmnisendClientInterface */
    private $omnisendClient;

    /** @var OrderRepositoryInterface */
    private $orderRepository;

    public function __construct(
        OmnisendClientInterface $omnisendClient,
        OrderRepositoryInterface $orderRepository
    ) {
        $this->omnisendClient = $omnisendClient;
        $this->orderRepository = $orderRepository;
    }

    public function __invoke(DeleteCart $message): void
    {
        $this->omnisendClient->deleteCart(
            $message->getOmnisendCartId(),
            $message->getChannelCode()
        );
        
        if (null === $message->getCartId()) {
            return;
        }

        /** @var OrderInterface|null $cart */
        $cart = $this->orderRepository->find($message->getCartId());

        if (null !== $cart) {
            $cart->getOmnisendOrderDetails()->setCartId(null);
            $this->orderRepository->add($cart);
        }
    }
}
