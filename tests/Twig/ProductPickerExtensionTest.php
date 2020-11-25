<?php

/*
 * This file is part of the NFQ package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
