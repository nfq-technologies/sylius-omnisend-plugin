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
        $rawData = $this->repository->findNotSyncedToOmnisend();
        $categories = [];
        $iteration = 1;

        foreach ($rawData as $row) {
            $item = $row[0];
            $categories[] = $this->categoryFactory->create($item, $message->getLocaleCode());
            $item->setPushedToOmnisend(new DateTime());
            $this->entityManager->persist($item);

            if (($iteration % $message->getBatchSize()) === 0) {
                $this->postData($categories, $message);
                $categories = [];
            }
            $iteration++;
        }

        if (!empty($categories)) {
            $this->postData($categories, $message);
        }
    }

    private function postData(array $categories, CreateBatch $message): void
    {
        $response = $this->omnisendClient->postBatch(
            $this->batchFactory->create(
                Batch::METHODS_POST,
                Batch::ENDPOINTS_CATEGORIES,
                $categories
            ),
            $message->getChannelCode()
        );

        if (null !== $response) {
            $this->entityManager->flush();
        }
    }
}
