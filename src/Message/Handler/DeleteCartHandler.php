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

use NFQ\SyliusOmnisendPlugin\Client\OmnisendClientInterface;
use NFQ\SyliusOmnisendPlugin\Message\Command\DeleteCart;
use NFQ\SyliusOmnisendPlugin\Model\OrderInterface;
use Psr\Log\LoggerAwareTrait;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;

class DeleteCartHandler
{
    use LoggerAwareTrait;

    /** @var OmnisendClientInterface */
    private $omnisendClient;

    public function __construct(
        OmnisendClientInterface $omnisendClient
    ) {
        $this->omnisendClient = $omnisendClient;
    }

    public function __invoke(DeleteCart $message): void
    {
        $this->omnisendClient->deleteCart($message->getOmnisendCartId(), $message->getChannelCode());
    }
}
