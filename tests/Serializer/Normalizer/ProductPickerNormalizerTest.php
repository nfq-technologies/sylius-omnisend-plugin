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

    public function data(): array
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
