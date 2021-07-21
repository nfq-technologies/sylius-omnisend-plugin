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

namespace NFQ\SyliusOmnisendPlugin\Twig;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use NFQ\SyliusOmnisendPlugin\Builder\ProductPickerBuilderDirectorInterface;
use Sylius\Component\Core\Model\ProductInterface;
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
            SerializationContext::create()->setSerializeNull(false),
        );
    }
}
