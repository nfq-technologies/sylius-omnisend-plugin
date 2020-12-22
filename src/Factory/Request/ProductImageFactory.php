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

namespace NFQ\SyliusOmnisendPlugin\Factory\Request;

use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\ProductImage;
use Sylius\Component\Core\Model\ProductImageInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;

class ProductImageFactory implements ProductImageFactoryInterface
{
    /** @var CacheManager */
    private $cache;

    /** @var string */
    private $imageFilter;

    public function __construct(
        CacheManager $cache,
        string $imageFilter
    ) {
        $this->cache = $cache;
        $this->imageFilter = $imageFilter;
    }

    public function create(ProductImageInterface $productImage, bool $default = false): ProductImage
    {
        /** @var ProductInterface|null $product */
        $product = $productImage->getOwner();
        $productVariants = [];
        if ($product !== null) {
            $productVariants = method_exists($product, 'getEnabledVariants') ? $product->getEnabledVariants() : $product->getVariants();
        }
        $variants = $productImage->getProductVariants()->isEmpty() ? $productVariants : $productImage->getProductVariants();

        return (new ProductImage())
            ->setImageID((string) $productImage->getId())
            ->setIsDefault($default)
            ->setUrl(null !== $productImage->getPath() ? $this->cache->generateUrl($productImage->getPath(), $this->imageFilter) : null)
            ->setVariantIDs(
                array_map(
                    function (ProductVariantInterface $productVariant): ?string {
                        return $productVariant->getCode();
                    },
                    $variants->toArray()
                )
            );
    }
}
