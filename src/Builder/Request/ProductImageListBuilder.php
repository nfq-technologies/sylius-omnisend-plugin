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

namespace NFQ\SyliusOmnisendPlugin\Builder\Request;

use NFQ\SyliusOmnisendPlugin\Factory\Request\ProductImageFactoryInterface;
use Sylius\Component\Core\Model\ProductImageInterface;
use Sylius\Component\Core\Model\ProductInterface;

class ProductImageListBuilder implements ProductImageListBuilderInterface
{
    const MAX_IMAGE_COUNT = 10;

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
            if ($count > self::MAX_IMAGE_COUNT) {
                break;
            }
            $images[] = $this->productImageFactory->create($image, $count === 0);
            $count++;
        }

        return $images;
    }
}
