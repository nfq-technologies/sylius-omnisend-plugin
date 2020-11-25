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
        $this->builder->addAdditionalData($product, $localeCode);

        return $this->builder->getProduct();
    }
}
