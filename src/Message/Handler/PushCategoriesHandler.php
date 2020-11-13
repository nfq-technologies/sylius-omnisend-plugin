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

use NFQ\SyliusOmnisendPlugin\Client\OmnisendClient;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\Batch;
use NFQ\SyliusOmnisendPlugin\Doctrine\ORM\TaxonRepositoryInterface;
use NFQ\SyliusOmnisendPlugin\Factory\Request\BatchFactoryInterface;
use NFQ\SyliusOmnisendPlugin\Factory\Request\CategoryFactoryInterface;
use NFQ\SyliusOmnisendPlugin\Message\Command\PushCategories;
use NFQ\SyliusOmnisendPlugin\Model\TaxonInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use DateTime;

class PushCategoriesHandler implements MessageHandlerInterface
{
    public const MAX_BATCH_SIZE = 1000;

    /** @var OmnisendClient */
    private $omnisendClient;

    /** @var CategoryFactoryInterface */
    private $categoryFactory;

    /** @var BatchFactoryInterface */
    private $batchFactory;

    /** @var TaxonRepositoryInterface */
    private $repository;

    public function __construct(
        OmnisendClient $omnisendClient,
        TaxonRepositoryInterface $repository,
        CategoryFactoryInterface $factory,
        BatchFactoryInterface $batchFactory
    )
    {
        $this->omnisendClient = $omnisendClient;
        $this->categoryFactory = $factory;
        $this->repository = $repository;
        $this->batchFactory = $batchFactory;
    }

    public function __invoke(PushCategories $message): void
    {
        $count = $this->repository->getNotSyncedToOmnisendCount();

        for ($i = 0; $i < ceil($count / self::MAX_BATCH_SIZE); $i++) {
            /** @var TaxonInterface[] $rawData */
            $rawData = $this->repository->findNotSyncedToOmnisend($i * self::MAX_BATCH_SIZE, self::MAX_BATCH_SIZE);
            $categories = [];

            foreach ($rawData as $item) {
                $categories[] = $this->categoryFactory->create($item);
            }

            if ($categories) {
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
                        $this->repository->add($item);//TODO IMPROVE
                    }
                }
            }
        }
    }
}
