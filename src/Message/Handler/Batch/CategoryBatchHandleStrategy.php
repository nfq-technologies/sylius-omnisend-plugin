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
use NFQ\SyliusOmnisendPlugin\Client\OmnisendClient;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\Batch;
use NFQ\SyliusOmnisendPlugin\Doctrine\ORM\TaxonRepositoryInterface;
use NFQ\SyliusOmnisendPlugin\Factory\Request\BatchFactoryInterface;
use NFQ\SyliusOmnisendPlugin\Factory\Request\CategoryFactoryInterface;
use NFQ\SyliusOmnisendPlugin\Message\Command\CreateBatch;
use NFQ\SyliusOmnisendPlugin\Model\TaxonInterface;
use DateTime;

class CategoryBatchHandleStrategy implements BatchHandlerStrategyInterface
{
    /** @var OmnisendClient */
    private $omnisendClient;

    /** @var CategoryFactoryInterface */
    private $categoryFactory;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var BatchFactoryInterface */
    private $batchFactory;

    /** @var TaxonRepositoryInterface */
    private $repository;

    public function __construct(
        OmnisendClient $omnisendClient,
        TaxonRepositoryInterface $repository,
        CategoryFactoryInterface $factory,
        BatchFactoryInterface $batchFactory,
        EntityManagerInterface $entityManager
    ) {
        $this->omnisendClient = $omnisendClient;
        $this->categoryFactory = $factory;
        $this->repository = $repository;
        $this->batchFactory = $batchFactory;
        $this->entityManager = $entityManager;
    }

    public function support(CreateBatch $batch): bool
    {
        return $batch->getType() == 'categories';
    }

    public function handle(CreateBatch $message): void
    {
        $count = $this->repository->getNotSyncedToOmnisendCount();

        for ($i = 0; $i < ceil($count / $message->getBatchSize()); $i++) {
            /** @var TaxonInterface[] $rawData */
            $rawData = $this->repository->findNotSyncedToOmnisend( $i * $message->getBatchSize(), $message->getBatchSize());
            $categories = [];

            foreach ($rawData as $item) {
                $categories[] = $this->categoryFactory->create($item, $message->getLocaleCode());
            }

            if (count($categories) > 0) {
                $response = $this->omnisendClient->postBatch(
                    $this->batchFactory->create(
                        Batch::METHODS_POST,
                        Batch::ENDPOINTS_CATEGORIES,
                        $categories
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
}
