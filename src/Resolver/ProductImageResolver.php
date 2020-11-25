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

namespace NFQ\SyliusOmnisendPlugin\Resolver;

use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Sylius\Component\Core\Model\ProductImageInterface;
use Sylius\Component\Core\Model\ProductInterface;

class ProductImageResolver implements ProductImageResolverInterface
{
    /** @var CacheManager */
    private $cache;

    /** @var string */
    private $imageType;

    /** @var string */
    private $imageFilter;

    /** @var string|null */
    private $defaultImage;

    public function __construct(
        CacheManager $cache,
        string $imageType,
        string $imageFilter,
        ?string $defaultImage = null
    ) {
        $this->cache = $cache;
        $this->imageType = $imageType;
        $this->imageFilter = $imageFilter;
        $this->defaultImage = $defaultImage;
    }

    public function resolve(ProductInterface $product): ?string
    {
        if ($product->getImagesByType($this->imageType)->count() > 0) {
            $images = $product->getImagesByType($this->imageType);
            /** @var ProductImageInterface $image */
            $image = $images->first();

            if (null !== $image->getPath()) {
                return $this->cache->generateUrl($image->getPath(), $this->imageFilter);
            }
        } elseif ($image = $product->getImages()->first()) {
            if (null !== $image->getPath()) {
                return $this->cache->generateUrl($image->getPath(), $this->imageFilter);
            }
        }

        return $this->defaultImage !== null ? $this->defaultImage : null;
    }
}
