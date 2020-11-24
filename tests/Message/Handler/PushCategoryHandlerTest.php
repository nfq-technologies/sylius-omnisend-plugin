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

namespace Tests\NFQ\SyliusOmnisendPlugin\Message\Handler;

use Doctrine\ORM\EntityManagerInterface;
use NFQ\SyliusOmnisendPlugin\Client\OmnisendClient;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\Category;
use NFQ\SyliusOmnisendPlugin\Client\Response\Model\BatchSuccess;
use NFQ\SyliusOmnisendPlugin\Factory\Request\BatchFactoryInterface;
use NFQ\SyliusOmnisendPlugin\Factory\Request\CategoryFactoryInterface;
use NFQ\SyliusOmnisendPlugin\Message\Command\CreateBatch;
use NFQ\SyliusOmnisendPlugin\Message\Handler\Batch\CategoryBatchHandleStrategy;
use PHPUnit\Framework\TestCase;
use NFQ\SyliusOmnisendPlugin\Doctrine\ORM\TaxonRepositoryInterface;
use Tests\NFQ\SyliusOmnisendPlugin\Application\Entity\Taxon;

class PushCategoryHandlerTest extends TestCase
{
    /** @var OmnisendClient */
    private $omnisendClient;

    /** @var CategoryFactoryInterface */
    private $categoryFactory;

    /** @var BatchFactoryInterface */
    private $batchFactory;

    /** @var TaxonRepositoryInterface */
    private $repository;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var CategoryBatchHandleStrategy */
    private $handler;

    protected function setUp(): void
    {
        $this->categoryFactory = $this->createMock(CategoryFactoryInterface::class);
        $this->omnisendClient = $this->createMock(OmnisendClient::class);
        $this->repository = $this->createMock(TaxonRepositoryInterface::class);
        $this->batchFactory = $this->createMock(BatchFactoryInterface::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);

        $this->handler = new CategoryBatchHandleStrategy(
            $this->omnisendClient,
            $this->repository,
            $this->categoryFactory,
            $this->batchFactory,
            $this->entityManager,
        );
    }

    /** @dataProvider data */
    public function testIfSplitsWell(int $productCount, int $iterationCount)
    {
        $message = new CreateBatch('category', 'en', 'en', 2);

        $taxons = [];

        for ($i = 0; $i < $productCount; $i++) {
            $taxons[] = [new Taxon()];
        }

        $this->repository
            ->expects($this->exactly(1))
            ->method('findNotSyncedToOmnisend')
            ->willReturn($taxons);

        $this->omnisendClient
            ->expects($this->exactly($iterationCount))
            ->method('postBatch')
            ->willReturn(new BatchSuccess());

        $this->categoryFactory
            ->expects($this->exactly($productCount))
            ->method('create')
            ->willReturn(new Category());

        $this->entityManager
            ->expects($this->exactly($productCount))
            ->method('persist');
        $this->entityManager
            ->expects($this->exactly($iterationCount))
            ->method('flush');

        $this->handler->handle($message);
    }

    public function data()
    {
        return [
            '0' => [
                0,
                0
            ],
            '1' => [
                1,
                1
            ],
            '2' => [
                2,
                1
            ],
            '3' => [
                3,
                2
            ],
            '5' => [
                5,
                3
            ]
        ];
    }
}
