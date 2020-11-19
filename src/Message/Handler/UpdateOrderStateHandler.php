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

use NFQ\SyliusOmnisendPlugin\Builder\Request\OrderBuilderDirectorInterface;
use NFQ\SyliusOmnisendPlugin\Client\OmnisendClientInterface;
use NFQ\SyliusOmnisendPlugin\Message\Command\CancelOrder;
use NFQ\SyliusOmnisendPlugin\Message\Command\UpdateOrder;
use NFQ\SyliusOmnisendPlugin\Message\Command\UpdateOrderState;
use NFQ\SyliusOmnisendPlugin\Model\OrderInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;

class UpdateOrderStateHandler
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

    public function __invoke(UpdateOrderState $message): void
    {
        /** @var OrderInterface $order */
        $order = $this->orderRepository->find($message->getOrderId());

        if (null === $order || null === $order->getOmnisendOrderDetails()->getCartId()()) {
            return;
        }

        $this->omnisendClient->patchOrder(
            $this->orderBuilderDirector->buildUpdatedStatesData($order),
            $message->getChannelCode()
        );
    }
}
