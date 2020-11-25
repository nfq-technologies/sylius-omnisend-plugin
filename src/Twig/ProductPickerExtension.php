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
    private $productPickerBuilderDirector;

    /** @var SerializerInterface */
    private $serializer;

    public function __construct(
        ProductPickerBuilderDirectorInterface $productPickerBuilder,
        SerializerInterface $serializer
    ) {
        $this->serializer = $serializer;
        $this->productPickerBuilderDirector = $productPickerBuilder;
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
            $this->productPickerBuilderDirector->build($product, $locale),
            'json',
            [
                AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
            ]
        );
    }
}
