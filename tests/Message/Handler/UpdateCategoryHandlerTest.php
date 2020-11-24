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

use NFQ\SyliusOmnisendPlugin\Client\OmnisendClient;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\Category;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\Contact;
use NFQ\SyliusOmnisendPlugin\Client\Response\Model\CategorySuccess;
use NFQ\SyliusOmnisendPlugin\Factory\Request\CategoryFactoryInterface;
use NFQ\SyliusOmnisendPlugin\Message\Command\CreateCategory;
use NFQ\SyliusOmnisendPlugin\Message\Command\UpdateCategory;
use NFQ\SyliusOmnisendPlugin\Message\Handler\CreateCategoryHandler;
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
        $message = (new UpdateCategory())
            ->setChannelCode('en')
            ->setTaxonCode('aa');

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
        $message = (new UpdateCategory())
            ->setChannelCode('en')
            ->setTaxonCode('aa');
        $taxon = new Taxon();

        $this->taxonRepository
            ->expects($this->exactly(1))
            ->method('findOneBy')
            ->willReturnCallback(function () use($taxon) { return $taxon; });
        $this->omnisendClient
            ->expects($this->exactly(1))
            ->method('postCategory')
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
        $message = (new UpdateCategory())
            ->setChannelCode('en')
            ->setTaxonCode('aa');
        $taxon = new Taxon();

        $this->taxonRepository
            ->expects($this->exactly(1))
            ->method('findOneBy')
            ->willReturn($taxon);
        $this->omnisendClient
            ->expects($this->exactly(1))
            ->method('postCategory')
            ->willReturn(new CategorySuccess());
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
        $message = (new UpdateCategory())
            ->setChannelCode('en')
            ->setTaxonCode('aa');
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
