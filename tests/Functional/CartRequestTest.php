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

namespace Tests\NFQ\SyliusOmnisendPlugin\Functional;

use Coduo\PHPMatcher\PHPUnit\PHPMatcherAssertions;
use NFQ\SyliusOmnisendPlugin\Builder\Request\CartBuilderDirectorInterface;
use NFQ\SyliusOmnisendPlugin\Builder\Request\ContactBuilderDirectorInterface;
use NFQ\SyliusOmnisendPlugin\Factory\Request\CategoryFactoryInterface;
use Sylius\Component\Core\Model\Address;
use Sylius\Component\Core\Model\AdjustmentInterface;
use Sylius\Component\Core\Model\OrderItem;
use Sylius\Component\Core\Model\OrderItemUnit;
use Sylius\Component\Core\Model\Product;
use Sylius\Component\Core\Model\ProductTranslation;
use Sylius\Component\Core\Model\ProductVariant;
use Sylius\Component\Order\Model\Adjustment;
use Sylius\Component\Taxonomy\Model\TaxonTranslation;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Tests\NFQ\SyliusOmnisendPlugin\Application\Entity\Customer;
use Tests\NFQ\SyliusOmnisendPlugin\Application\Entity\Order;
use Tests\NFQ\SyliusOmnisendPlugin\Application\Entity\Taxon;
use Tests\NFQ\SyliusOmnisendPlugin\Mock\OrderMock;

class CartRequestTest extends WebTestCase
{
    use PHPMatcherAssertions;

    /** @var SerializerInterface */
    private $serializer;

    /** @var CartBuilderDirectorInterface */
    private $director;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->serializer = self::$container->get('serializer');
        $this->director = self::$container->get('nfq_sylius_omnisend_plugin.builder.request.cart_director');
    }

    /** @dataProvider data */
    public function testIfFormatValidRequest(OrderMock $data, string $result)
    {
        $this->assertMatchesPattern(
            $result,
            $this->serializer->serialize(
                $this->director->build($data, null),
                'json',
                [
                    AbstractObjectNormalizer::SKIP_NULL_VALUES => true
                ]
            )
        );
    }

    public function data(): array
    {
        $order = new OrderMock();
        $order->setCurrencyCode('EUR');
        $order->setLocaleCode('en');
        $orderItem = new OrderItem();
        $orderItem->setOrder($order);
        $orderItem->setUnitPrice(5000);
        $orderItem->addUnit(new OrderItemUnit($orderItem));
        $orderItem->setProductName('Name');
        $adjustment1 = new Adjustment();
        $adjustment1->setAmount(-1000);
        $adjustment1->setType(AdjustmentInterface::ORDER_ITEM_PROMOTION_ADJUSTMENT);
        $orderItem->addAdjustment($adjustment1);
        $variant = new ProductVariant();
        $product = new Product();
        $productTranslation = new ProductTranslation();
        $productTranslation->setLocale('en');
        $productTranslation->setSlug('product');
        $product->addTranslation($productTranslation);
        $variant->setProduct($product);
        $product->setCode('product_code');
        $variant->setCode('variant_code');
        $orderItem->setVariant($variant);
        $orderCustomer = new \Sylius\Component\Core\Model\Customer();
        $orderCustomer->setEmail('test@nfq.lt');
        $orderCustomer->setPhoneNumber('4444');
        $order->setCustomer($orderCustomer);

        return [
            'order' => [
                $order,
                <<<JSON
                {
                  "cartID": "@string@",
                  "cartRecoveryUrl": "@string@",
                  "cartSum": "@integer@",
                  "createdAt": "@string@",
                  "currency": "@string@",
                  "email": "@string@",
                  "phone": "@string@",
                  "products": [
                    {
                      "cartProductID": "@string@",
                      "discount": "@integer@",
                      "imageUrl": "@string@",
                      "oldPrice": "@integer@",
                      "price": "@integer@",
                      "productID": "@string@",
                      "productUrl": "@string@",
                      "quantity": "@integer@",
                      "sku": "@string@",
                      "title": "@string@",
                      "variantID": "@string@"
                    }
                  ]
                }
            JSON,
            ],
        ];
    }
}
