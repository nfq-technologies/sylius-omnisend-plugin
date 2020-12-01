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

namespace Tests\NFQ\SyliusOmnisendPlugin\Resolver;

use NFQ\SyliusOmnisendPlugin\Resolver\ProductUrlResolver;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\Product;
use Sylius\Component\Core\Model\ProductTranslation;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class ProductUrlResolverTest extends TestCase
{
    /** @var ProductUrlResolver */
    private $resolver;

    /** @var RouterInterface */
    private $router;

    protected function setUp(): void
    {
        $this->router = $this->createMock(RouterInterface::class);
        $this->resolver = new ProductUrlResolver($this->router);
    }

    public function testIfFormatsWell()
    {
        $product = new Product();
        $productTranslation = new ProductTranslation();
        $productTranslation->setLocale('en');
        $productTranslation->setSlug('en_slug');
        $productTranslation2 = new ProductTranslation();
        $productTranslation2->setLocale('es');
        $productTranslation2->setSlug('es_slug');
        $product->addTranslation($productTranslation2);
        $product->addTranslation($productTranslation);
        $product->setCurrentLocale('es');
        $this->router
            ->expects($this->once())
            ->method('generate')
            ->willReturnCallback(
                function ($route, $config, $type) {
                    $this->assertEquals($route, 'sylius_shop_product_show');
                    $this->assertEquals($config, [
                        'slug' => 'en_slug',
                        '_locale' => 'en',
                    ]);
                    $this->assertEquals($type, UrlGeneratorInterface::ABSOLUTE_URL);

                    return 'en_slug';
                }
            );
        $this->resolver->resolve($product, 'en');
    }
}
