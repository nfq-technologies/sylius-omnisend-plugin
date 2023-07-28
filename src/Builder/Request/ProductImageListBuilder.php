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

namespace NFQ\SyliusOmnisendPlugin\Builder\Request;

use NFQ\SyliusOmnisendPlugin\Factory\Request\ProductImageFactoryInterface;
use Sylius\Component\Core\Model\ProductImageInterface;
use Sylius\Component\Core\Model\ProductInterface;

class ProductImageListBuilder implements ProductImageListBuilderInterface
{
    public const MAX_IMAGE_COUNT = 10;

    /** @var ProductImageFactoryInterface */
    private $productImageFactory;

    /** @var string|null */
    private $imageType;

    public function __construct(ProductImageFactoryInterface $productImageFactory, ?string $imageType)
    {
        $this->productImageFactory = $productImageFactory;
        $this->imageType = $imageType;
    }

    public function build(ProductInterface $product): array
    {
        $count = 0;
        $images = [];
        $productImages = null !== $this->imageType ? $product->getImagesByType($this->imageType) : $product->getImages();

        /** @var ProductImageInterface $image */
        foreach ($productImages as $image) {
            if ($count === self::MAX_IMAGE_COUNT) {
                break;
            }
            $images[] = $this->productImageFactory->create($image, $count === 0);
            ++$count;
        }

        return $images;
    }
}
