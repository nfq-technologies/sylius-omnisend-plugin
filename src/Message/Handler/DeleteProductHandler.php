<?php

/*
 * This file is part of the NFQ package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace NFQ\SyliusOmnisendPlugin\Message\Handler;

use NFQ\SyliusOmnisendPlugin\Client\OmnisendClientInterface;
use NFQ\SyliusOmnisendPlugin\Message\Command\DeleteCategory;
use NFQ\SyliusOmnisendPlugin\Message\Command\DeleteProduct;
use NFQ\SyliusOmnisendPlugin\Model\TaxonInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class DeleteProductHandler implements MessageHandlerInterface
{
    /** @var OmnisendClientInterface */
    private $omnisendClient;

    /** @var ProductRepositoryInterface */
    private $productRepository;

    public function __construct(
        OmnisendClientInterface $omnisendClient,
        ProductRepositoryInterface $productRepository
    ) {
        $this->omnisendClient = $omnisendClient;
        $this->productRepository = $productRepository;
    }

    public function __invoke(DeleteProduct $message): void
    {
        if (null !== $message->getProductCode()) {
            $this->omnisendClient->deleteProduct(
                $message->getProductCode(),
                $message->getChannelCode()
            );
        }
    }
}
