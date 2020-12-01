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

use NFQ\SyliusOmnisendPlugin\Builder\Request\CartBuilderDirectorInterface;
use NFQ\SyliusOmnisendPlugin\Client\OmnisendClientInterface;
use NFQ\SyliusOmnisendPlugin\Client\Response\Model\CartSuccess;
use NFQ\SyliusOmnisendPlugin\Message\Command\UpdateCart;
use NFQ\SyliusOmnisendPlugin\Model\OrderInterface;
use Psr\Log\LoggerAwareTrait;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Throwable;

class UpdateCartHandler
{
    use LoggerAwareTrait;

    /** @var OrderRepositoryInterface */
    private $orderRepository;

    /** @var CartBuilderDirectorInterface */
    private $cartBuilderDirector;

    /** @var OmnisendClientInterface */
    private $omnisendClient;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        CartBuilderDirectorInterface $cartBuilderDirector,
        OmnisendClientInterface $omnisendClient
    ) {
        $this->orderRepository = $orderRepository;
        $this->cartBuilderDirector = $cartBuilderDirector;
        $this->omnisendClient = $omnisendClient;
    }

    public function __invoke(UpdateCart $message): void
    {
        /** @var OrderInterface|null $order */
        $order = $this->orderRepository->find($message->getOrderId());

        if (null !== $order) {
            try {
                $this->handle($order, $message->getContactId());
            } catch (Throwable $exception) {
                if (null !== $this->logger) {
                    $this->logger->error(
                        'Omnisend cart update action failed.',
                        [
                            'error' => $exception->getMessage(),
                        ]
                    );
                }
            }
        }
    }

    public function handle(OrderInterface $order, ?string $contactId = null): void
    {
        if (null === $order->getOmnisendOrderDetails()->getCartId()) {
            /** @var CartSuccess|null $response */
            $response = $this->omnisendClient->postCart(
                $this->cartBuilderDirector->build($order, $contactId),
                null !== $order->getChannel() ? $order->getChannel()->getCode() : null
            );

            if (null !== $response) {
                $order->getOmnisendOrderDetails()->setOrder($order);
                $order->getOmnisendOrderDetails()->setCartId($response->getCartID());
                $this->orderRepository->add($order);
            }
        } else {
            $this->omnisendClient->patchCart(
                $this->cartBuilderDirector->build($order, $contactId),
                null !== $order->getChannel() ? $order->getChannel()->getCode() : null
            );
        }
    }
}
