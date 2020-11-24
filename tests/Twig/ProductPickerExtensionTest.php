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

namespace Tests\NFQ\SyliusOmnisendPlugin\Twig;

use NFQ\SyliusOmnisendPlugin\Builder\ProductPickerBuilderDirectorInterface;
use NFQ\SyliusOmnisendPlugin\Model\ProductPicker;
use NFQ\SyliusOmnisendPlugin\Twig\ProductPickerExtension;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\Product;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class ProductPickerExtensionTest extends TestCase
{
    /** @var ProductPickerExtension */
    private $extension;

    /** @var ProductPickerBuilderDirectorInterface */
    private $pickerBuilderDirector;

    /** @var SerializerInterface */
    private $serializer;

    protected function setUp(): void
    {
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->pickerBuilderDirector = $this->createMock(ProductPickerBuilderDirectorInterface::class);

        $this->extension = new ProductPickerExtension(
            $this->pickerBuilderDirector,
            $this->serializer
        );
    }

    public function testIfFormatData()
    {
        $this->pickerBuilderDirector
            ->expects($this->once())
            ->method('build')
            ->willReturn(new ProductPicker());
        $this->serializer
            ->expects($this->once())
            ->method('serialize')
            ->willReturnCallback(
                function ($data, $type, $config) {
                    $this->assertEquals(new ProductPicker(), $data);
                    $this->assertEquals('json', $type);
                    $this->assertEquals(
                        [
                            AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
                        ],
                        $config
                    );

                    return 'test';
                }
            );

        $this->extension->getProductPickerData(new Product(), 'en');
    }
}
