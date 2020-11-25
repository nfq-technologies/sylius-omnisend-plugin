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

        if (count($categories) !== 0) {
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
