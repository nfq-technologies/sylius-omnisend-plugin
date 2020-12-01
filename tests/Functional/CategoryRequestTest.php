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

namespace Tests\NFQ\SyliusOmnisendPlugin\Functional;

use Coduo\PHPMatcher\PHPUnit\PHPMatcherAssertions;
use NFQ\SyliusOmnisendPlugin\Builder\Request\ContactBuilderDirectorInterface;
use NFQ\SyliusOmnisendPlugin\Factory\Request\CategoryFactoryInterface;
use Sylius\Component\Core\Model\Address;
use Sylius\Component\Taxonomy\Model\TaxonTranslation;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Tests\NFQ\SyliusOmnisendPlugin\Application\Entity\Customer;
use Tests\NFQ\SyliusOmnisendPlugin\Application\Entity\Taxon;

class CategoryRequestTest extends WebTestCase
{
    use PHPMatcherAssertions;

    /** @var SerializerInterface */
    private $serializer;

    /** @var CategoryFactoryInterface */
    private $factory;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->serializer = self::$container->get('serializer');
        $this->factory = self::$container->get('nfq_sylius_omnisend_plugin.factory.request.category');
    }

    /** @dataProvider data */
    public function testIfFormatValidRequest(Taxon $data, string $result)
    {
        $this->assertMatchesPattern(
            $result,
            $this->serializer->serialize(
                $this->factory->create($data),
                'json',
                [
                    AbstractObjectNormalizer::SKIP_NULL_VALUES => true
                ]
            )
        );
    }

    public function data(): array
    {
        $taxon = new Taxon();
        $taxonTranslation = new TaxonTranslation();
        $taxonTranslation->setLocale('en');
        $taxonTranslation->setName('TEST');
        $taxon->setCode('code');
        $taxon->setCurrentLocale('en');
        $taxon->addTranslation($taxonTranslation);
        $taxon->setCreatedAt(new \DateTime('2010-12-12 12:12:12'));

        return [
            'Taxon' => [
                $taxon,
                <<<JSON
                {
                  "categoryID": "@string@",
                  "title": "@string@",
                  "createdAt": "@string@"
                }
            JSON,
            ],
        ];
    }
}
