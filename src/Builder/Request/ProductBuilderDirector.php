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

use NFQ\SyliusOmnisendPlugin\Client\Request\Model\Product;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ProductInterface;

class ProductBuilderDirector implements ProductBuilderDirectorInterface
{
    /** @var ProductBuilderInterface */
    private $builder;

    public function __construct(ProductBuilderInterface $builder)
    {
        $this->builder = $builder;
    }

    public function build(ProductInterface $product, ChannelInterface $channel, ?string $localeCode = null): Product
    {
        $this->builder->createProduct();
        $this->builder->addImages($product);
        $this->builder->addVariants($product, $channel, $localeCode);
        $this->builder->addContentData($product, $channel, $localeCode);
        $this->builder->addStockStatus($product);
        $this->builder->addAdditionalData($product);

        return $this->builder->getProduct();
    }
}
