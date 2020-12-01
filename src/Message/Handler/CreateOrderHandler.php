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

use NFQ\SyliusOmnisendPlugin\Builder\Request\OrderBuilderDirectorInterface;
use NFQ\SyliusOmnisendPlugin\Client\OmnisendClientInterface;
use NFQ\SyliusOmnisendPlugin\Message\Command\CreateOrder;
use NFQ\SyliusOmnisendPlugin\Model\OrderInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;

class CreateOrderHandler
{
    /** @var OmnisendClientInterface */
    private $omnisendClient;

    /** @var OrderRepositoryInterface */
    private $orderRepository;

    /** @var OrderBuilderDirectorInterface */
    private $orderBuilderDirector;

    public function __construct(
        OmnisendClientInterface $omnisendClient,
        OrderRepositoryInterface $orderRepository,
        OrderBuilderDirectorInterface $orderBuilderDirector
    ) {
        $this->omnisendClient = $omnisendClient;
        $this->orderRepository = $orderRepository;
        $this->orderBuilderDirector = $orderBuilderDirector;
    }

    public function __invoke(CreateOrder $message): void
    {
        /** @var OrderInterface|null $order */
        $order = $this->orderRepository->find($message->getOrderId());

        if (null === $order || null === $order->getOmnisendOrderDetails()->getCartId()) {
            return;
        }

        $this->omnisendClient->postOrder(
            $this->orderBuilderDirector->build($order),
            $message->getChannelCode()
        );
    }
}
