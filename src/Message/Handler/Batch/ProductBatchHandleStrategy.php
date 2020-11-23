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

namespace NFQ\SyliusOmnisendPlugin\Message\Handler\Batch;

use Doctrine\ORM\EntityManagerInterface;
use NFQ\SyliusOmnisendPlugin\Builder\Request\ProductBuilderDirectorInterface;
use NFQ\SyliusOmnisendPlugin\Client\OmnisendClient;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\Batch;
use NFQ\SyliusOmnisendPlugin\Doctrine\ORM\ProductRepositoryInterface;
use NFQ\SyliusOmnisendPlugin\Factory\Request\BatchFactoryInterface;
use NFQ\SyliusOmnisendPlugin\Message\Command\CreateBatch;
use NFQ\SyliusOmnisendPlugin\Model\TaxonInterface;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use DateTime;

class ProductBatchHandleStrategy implements BatchHandlerStrategyInterface
{
    /** @var OmnisendClient */
    private $omnisendClient;

    /** @var ProductBuilderDirectorInterface */
    private $productBuilderDirectory;

    /** @var BatchFactoryInterface */
    private $batchFactory;

    /** @var ProductRepositoryInterface */
    private $repository;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var ChannelRepositoryInterface */
    private $channelRepository;

    public function __construct(
        OmnisendClient $omnisendClient,
        ProductBuilderDirectorInterface $productBuilderDirectory,
        BatchFactoryInterface $batchFactory,
        ProductRepositoryInterface $repository,
        EntityManagerInterface $entityManager,
        ChannelRepositoryInterface $channelRepository
    ) {
        $this->omnisendClient = $omnisendClient;
        $this->productBuilderDirectory = $productBuilderDirectory;
        $this->batchFactory = $batchFactory;
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->channelRepository = $channelRepository;
    }

    public function support(CreateBatch $batch): bool
    {
        return $batch->getType() === 'products';
    }

    public function handle(CreateBatch $message): void
    {
        /** @var ChannelInterface $channel */
        $channel = $this->channelRepository->findOneBy(['code' => $message->getChannelCode()]);
        $this->updateProducts($channel, $message);
        $this->createProducts($channel, $message);
    }

    private function createProducts(ChannelInterface $channel, CreateBatch $message): void
    {
        $count = $this->repository->getNotSyncedToOmnisendCount($channel);

        for ($i = 0; $i < ceil($count / $message->getBatchSize()); $i++) {
            /** @var TaxonInterface[] $rawData */
            $rawData = $this->repository->findNotSyncedToOmnisend($i * $message->getBatchSize(), $message->getBatchSize(), $channel);
            $this->pushData($rawData, $message, $channel, Batch::METHODS_POST);
        }
    }

    private function updateProducts(ChannelInterface $channel, CreateBatch $message): void
    {
        $count = $this->repository->getSyncedToOmnisendCount($channel);

        for ($i = 0; $i < ceil($count / $message->getBatchSize()); $i++) {
            /** @var TaxonInterface[] $rawData */
            $rawData = $this->repository->findSyncedToOmnisend($i * $message->getBatchSize(), $message->getBatchSize(), $channel);
            $this->pushData($rawData, $message, $channel, Batch::METHODS_PUT);
        }
    }

    public function pushData(array $rawData, CreateBatch $message, ChannelInterface $channel, string $method): void
    {
        $resources = [];

        foreach ($rawData as $item) {
            $resources[] = $this->productBuilderDirectory->build($item, $channel, $message->getLocaleCode());
        }

        if (count($resources) > 0) {
            $response = $this->omnisendClient->postBatch(
                $this->batchFactory->create(
                    $method,
                    Batch::ENDPOINTS_PRODUCT,
                    $resources
                ),
                $message->getChannelCode()
            );

            if (null !== $response) {
                foreach ($rawData as $item) {
                    $item->setPushedToOmnisend(new DateTime());
                    $this->entityManager->persist($item);
                }
                $this->entityManager->flush();
            }
        }
    }
}
