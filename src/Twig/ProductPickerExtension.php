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

namespace NFQ\SyliusOmnisendPlugin\Twig;

use NFQ\SyliusOmnisendPlugin\Builder\ProductPickerBuilderDirectorInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ProductPickerExtension extends AbstractExtension
{
    /** @var ProductPickerBuilderDirectorInterface */
    private $productPickerBuilder;

    /** @var SerializerInterface */
    private $serializer;

    public function __construct(
        ProductPickerBuilderDirectorInterface $productPickerBuilder,
        SerializerInterface $serializer
    ) {
        $this->serializer = $serializer;
        $this->productPickerBuilder = $productPickerBuilder;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('nfq_sylius_omnisend_product_picker', [$this, 'getProductPickerData']),
        ];
    }

    public function getProductPickerData(ProductInterface $product, string $locale): string
    {
        return $this->serializer->serialize(
            $this->productPickerBuilder->build($product, $locale),
            'json',
            [
                AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
            ]
        );
    }
}
