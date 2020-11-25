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

use Sylius\Component\Core\Model\ProductInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class ProductUrlResolver implements ProductUrlResolverInterface
{
    /** @var string */
    private const PRODUCT_ROUTE_NAME = 'sylius_shop_product_show';

    /** @var RouterInterface */
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function resolve(ProductInterface $product, ?string $localeCode = null): ?string
    {
        $translation = $product->getTranslation($localeCode);

        return $this->router->generate(
            self::PRODUCT_ROUTE_NAME,
            [
                'slug' => $translation->getSlug(),
                '_locale' => $localeCode,
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }
}
