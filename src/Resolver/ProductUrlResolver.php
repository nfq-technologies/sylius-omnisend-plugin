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
                '_locale' => $localeCode
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }
}
