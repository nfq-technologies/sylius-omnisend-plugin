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

namespace Tests\NFQ\SyliusOmnisendPlugin\Factory\Request;

use NFQ\SyliusOmnisendPlugin\Factory\Request\CategoryFactory;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Taxonomy\Model\TaxonTranslation;
use Tests\NFQ\SyliusOmnisendPlugin\Application\Entity\Taxon;

class CategoryFactoryTest extends TestCase
{
    /** @var CategoryFactory */
    private $factory;

    protected function setUp(): void
    {
        $this->factory = new CategoryFactory();
    }

    public function testIfCreatesWell()
    {
        $taxon = new Taxon();
        $taxonTranslation = new TaxonTranslation();
        $taxonTranslation->setLocale('en');
        $taxonTranslation->setName('TEST');
        $taxon->setCode('code');
        $taxon->setCurrentLocale('en');
        $taxon->addTranslation($taxonTranslation);
        $taxon->setCreatedAt(new \DateTime('2010-12-12 12:12:12'));
        $result = $this->factory->create($taxon);

        $this->assertEquals($result->getCategoryID(), 'code');
        $this->assertEquals($result->getTitle(), 'TEST');
        $this->assertEquals($result->getCreatedAt(), '2010-12-12T12:12:12+00:00');
        $this->assertEquals($result->getUpdatedAt(), null);
    }
}
