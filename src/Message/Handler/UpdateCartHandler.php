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
        /** @var OrderInterface $order */
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

    public function handle(OrderInterface $order, ?string $contactId = null)
    {
        if (null === $order->getOmnisendOrderDetails()->getCartId()) {
            /** @var CartSuccess $response */
            $response = $this->omnisendClient->postCart(
                $this->cartBuilderDirector->build($order, $contactId),
                $order->getChannel()->getCode()
            );

            if (null !== $response) {
                $order->getOmnisendOrderDetails()->setOrder($order);
                $order->getOmnisendOrderDetails()->setCartId($response->getCartID());
                $this->orderRepository->add($order);
            }
        } else {
            $this->omnisendClient->patchCart(
                $this->cartBuilderDirector->build($order, $contactId),
                $order->getChannel()->getCode()
            );
        }
    }
}
