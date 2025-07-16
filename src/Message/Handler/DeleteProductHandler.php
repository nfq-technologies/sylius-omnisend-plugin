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
use NFQ\SyliusOmnisendPlugin\Message\Command\DeleteProduct;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class DeleteProductHandler implements MessageHandlerInterface
{
    public function __construct(
        private readonly OmnisendClientInterface $omnisendClient,
    ) {
    }

    public function __invoke(DeleteProduct $message): void
    {
        if ($message->getProductCode() !== null) {
            $this->omnisendClient->deleteProduct(
                $message->getProductCode(),
                $message->getChannelCode()
            );
        }
    }
}
