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

namespace NFQ\SyliusOmnisendPlugin\Resolver;

use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Sylius\Component\Core\Model\ProductImageInterface;
use Sylius\Component\Core\Model\ProductInterface;

class ProductImageResolver implements ProductImageResolverInterface
{
    /**
     * @var CacheManager
     */
    private $cache;

    /**
     * @var string
     */
    private $imageType;

    /**
     * @var string
     */
    private $imageFilter;

    /**
     * @var string|null
     */
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
        if ($product->getImagesByType($this->imageType)->count()) {
            $images = $product->getImagesByType($this->imageType);
            /** @var ProductImageInterface $image */
            $image = $images->first();

            return $this->cache->resolve($image->getPath(), $this->imageFilter);
        } elseif ($image = $product->getImages()->first()) {
            return $this->cache->resolve($image->getPath(), $this->imageFilter);
        }

        return $this->defaultImage ?: null;
    }
}
