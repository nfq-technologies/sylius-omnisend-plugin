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

namespace Tests\NFQ\SyliusOmnisendPlugin\Serializer\Normalizer;

use NFQ\SyliusOmnisendPlugin\Model\ProductPicker;
use NFQ\SyliusOmnisendPlugin\Serializer\Normalizer\ProductPickerNormalizer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use DateTime;
use stdClass;

class ProductPickerNormalizerTest extends TestCase
{
    /**
     * @var ProductPickerNormalizer
     */
    private $normalizer;

    /**
     * @var NormalizerInterface
     */
    private $baseNormalizer;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->baseNormalizer = $this->createMock(NormalizerInterface::class);
        $this->normalizer = new ProductPickerNormalizer($this->baseNormalizer);
    }

    public function testIfCallParentNormalizer()
    {
        $this->baseNormalizer->expects($this->once())->method('normalize')->willReturn('');
        $this->normalizer->normalize(new ProductPicker());
    }

    /**
     * @dataProvider data
     */
    public function testIfSupport($normalizeData, string $type, bool $isSupport): void
    {
        $this->assertEquals($this->normalizer->supportsNormalization($normalizeData, $type), $isSupport);
    }

    public static function data(): array
    {
        return [
            'empty string' => [
                '',
                'json',
                false,
            ],
            'null' => [
                null,
                'json',
                false,
            ],
            'custom datetime' => [
                new DateTime(),
                'json',
                false,
            ],
            'custom object' => [
                new stdClass(),
                'json',
                false,
            ],
            'product picker json' => [
                new ProductPicker(),
                'json',
                true,
            ],
            'product picker xml' => [
                new stdClass(),
                'xml',
                false,
            ],
        ];
    }
}
