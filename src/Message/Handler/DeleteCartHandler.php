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

class DeleteCartHandler
{
    /** @var OmnisendClientInterface */
    private $omnisendClient;

    public function __construct(
        OmnisendClientInterface $omnisendClient
    ) {
        $this->omnisendClient = $omnisendClient;
    }

    public function __invoke(DeleteCart $message): void
    {
        $this->omnisendClient->deleteCart(
            $message->getOmnisendCartId(),
            $message->getChannelCode()
        );
    }
}
