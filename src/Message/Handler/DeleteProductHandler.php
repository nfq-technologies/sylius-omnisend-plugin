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
        $this->omnisendClient->deleteProduct($message->getProductCode(), $message->getChannelCode());
    }
}
