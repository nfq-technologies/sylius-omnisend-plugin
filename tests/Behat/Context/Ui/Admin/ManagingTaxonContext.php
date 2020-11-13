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

namespace Tests\NFQ\SyliusOmnisendPlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use NFQ\SyliusOmnisendPlugin\Doctrine\ORM\TaxonRepositoryInterface;
use NFQ\SyliusOmnisendPlugin\Model\TaxonInterface;
use Webmozart\Assert\Assert;

class ManagingTaxonContext implements Context
{
    /** @var TaxonRepositoryInterface */
    private $taxonRepository;

    public function __construct(TaxonRepositoryInterface $taxonRepository)
    {
        $this->taxonRepository = $taxonRepository;
    }

    /** @Then Taxon with a code :code should be pushed to Omnisend */
    public function customerHasOmnisendClientId(string $code): void
    {
        /** @var TaxonInterface $taxon */
        $taxon = $this->taxonRepository->findOneBy(['code' => $code]);

        Assert::isInstanceOf($taxon, TaxonInterface::class);
        Assert::true($taxon->isPushedToOmnisend());
    }
}
