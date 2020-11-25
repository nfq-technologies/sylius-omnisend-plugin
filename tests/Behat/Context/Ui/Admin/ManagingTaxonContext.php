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

namespace Tests\NFQ\SyliusOmnisendPlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use NFQ\SyliusOmnisendPlugin\Doctrine\ORM\TaxonRepositoryInterface;
use NFQ\SyliusOmnisendPlugin\Model\TaxonInterface;
use Sylius\Behat\Service\SharedStorageInterface;
use Webmozart\Assert\Assert;

class ManagingTaxonContext implements Context
{
    /** @var TaxonRepositoryInterface */
    private $taxonRepository;

    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(
        TaxonRepositoryInterface $taxonRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
        $this->taxonRepository = $taxonRepository;
    }

    /** @Then Taxon with a code :code should be pushed to Omnisend */
    public function customerHasOmnisendClientId(string $code): void
    {
        /** @var TaxonInterface $taxon */
        $taxon = $this->taxonRepository->findOneBy(['code' => $code]);
        $this->entityManager->refresh($taxon);

        Assert::isInstanceOf($taxon, TaxonInterface::class);
        Assert::true($taxon->isPushedToOmnisend());
    }
}
