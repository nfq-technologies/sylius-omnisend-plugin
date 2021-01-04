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

namespace Tests\NFQ\SyliusOmnisendPlugin\Message\Handler;

use NFQ\SyliusOmnisendPlugin\Client\OmnisendClient;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\Category;
use NFQ\SyliusOmnisendPlugin\Client\Response\Model\CategorySuccess;
use NFQ\SyliusOmnisendPlugin\Factory\Request\CategoryFactoryInterface;
use NFQ\SyliusOmnisendPlugin\Message\Command\UpdateCategory;
use NFQ\SyliusOmnisendPlugin\Message\Handler\UpdateCategoryHandler;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Tests\NFQ\SyliusOmnisendPlugin\Application\Entity\Taxon;

class UpdateCategoryHandlerTest extends TestCase
{
    /** @var OmnisendClient */
    private $omnisendClient;

    /** @var TaxonRepositoryInterface */
    private $taxonRepository;

    /** @var CategoryFactoryInterface */
    private $categoryFactory;

    /** @var UpdateCategoryHandler */
    private $handler;

    protected function setUp(): void
    {
        $this->categoryFactory = $this->createMock(CategoryFactoryInterface::class);
        $this->omnisendClient = $this->createMock(OmnisendClient::class);
        $this->taxonRepository = $this->createMock(TaxonRepositoryInterface::class);

        $this->handler = new UpdateCategoryHandler(
            $this->omnisendClient,
            $this->taxonRepository,
            $this->categoryFactory
        );
    }

    public function testIfAddOmnisendFlagIfTaxonDoesNotExists()
    {
        $message = new UpdateCategory('aa', 'en');

        $this->taxonRepository
            ->expects($this->exactly(1))
            ->method('findOneBy')
            ->willReturn(null);
        $this->omnisendClient
            ->expects($this->exactly(0))
            ->method('postCategory');
        $this->categoryFactory
            ->expects($this->exactly(0))
            ->method('create');

        $this->handler->__invoke($message);
    }

    public function testIfAddOmnisendFlagWithIfResponseIsInvalid()
    {
        $message = new UpdateCategory('aa', 'en');
        $taxon = new Taxon();
        $taxon->setCode('code');

        $this->taxonRepository
            ->expects($this->exactly(1))
            ->method('findOneBy')
            ->willReturnCallback(function () use($taxon) { return $taxon; });
        $this->omnisendClient
            ->expects($this->exactly(1))
            ->method('postCategory')
            ->willReturn(null);
        $this->omnisendClient
            ->expects($this->exactly(1))
            ->method('getCategory')
            ->willReturn(null);
        $this->categoryFactory
            ->expects($this->exactly(1))
            ->method('create')
            ->willReturn(new Category());
        $this->handler->__invoke($message);

        $this->assertFalse($taxon->isPushedToOmnisend());
    }

    public function testIfAddOmnisendFlag()
    {
        $message = new UpdateCategory('aa', 'en');
        $taxon = new Taxon();
        $taxon->setCode('code');

        $this->taxonRepository
            ->expects($this->exactly(1))
            ->method('findOneBy')
            ->willReturn($taxon);
        $this->omnisendClient
            ->expects($this->exactly(1))
            ->method('postCategory')
            ->willReturn(new CategorySuccess());
        $this->omnisendClient
            ->expects($this->exactly(1))
            ->method('getCategory')
            ->willReturn(null);
        $this->categoryFactory
            ->expects($this->exactly(1))
            ->method('create')
            ->with($taxon)
            ->willReturn(new Category());
        $this->handler->__invoke($message);

        $this->assertTrue($taxon->isPushedToOmnisend());
    }

    public function testIfSendUpdateRequestIfOmnisendFlagIsAlreadySet()
    {
        $message = new UpdateCategory('aa', 'en');
        $taxon = new Taxon();
        $taxon->setPushedToOmnisend(new \DateTime('2012-12-12'));

        $this->taxonRepository
            ->expects($this->exactly(1))
            ->method('findOneBy')
            ->willReturn($taxon);
        $this->omnisendClient
            ->expects($this->exactly(1))
            ->method('putCategory')
            ->willReturn(new CategorySuccess());
        $this->categoryFactory
            ->expects($this->exactly(1))
            ->method('create')
            ->with($taxon)
            ->willReturn(new Category());
        $this->handler->__invoke($message);

        $this->assertTrue($taxon->isPushedToOmnisend());
    }
}
