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

namespace NFQ\SyliusOmnisendPlugin\Factory\Request;

use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\ProductImage;
use Sylius\Component\Core\Model\ProductImageInterface;
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

    public function create(ProductImageInterface $productImage, bool $default = false)
    {
        $variants = $productImage->getProductVariants()->count() === 0 ? $productImage->getOwner()->getEnabledVariants() : $productImage->getProductVariants();

        return (new ProductImage())
            ->setImageID((string)$productImage->getId())
            ->setIsDefault($default)
            ->setUrl($this->cache->generateUrl($productImage->getPath(), $this->imageFilter))
            ->setVariantIDs(
                array_map(
                    function (ProductVariantInterface $productVariant): string {
                        return $productVariant->getCode();
                    },
                    $variants->toArray()
                )
            );
    }
}
