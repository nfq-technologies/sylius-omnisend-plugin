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

namespace Tests\NFQ\SyliusOmnisendPlugin\Functional;

use Coduo\PHPMatcher\PHPUnit\PHPMatcherAssertions;
use NFQ\SyliusOmnisendPlugin\Builder\Request\OrderBuilderDirectorInterface;
use Sylius\Component\Core\Model\Address;
use Sylius\Component\Core\Model\AdjustmentInterface;
use Sylius\Component\Core\Model\OrderItem;
use Sylius\Component\Core\Model\OrderItemUnit;
use Sylius\Component\Core\Model\Payment;
use Sylius\Component\Core\Model\PaymentMethod;
use Sylius\Component\Core\Model\Product;
use Sylius\Component\Core\Model\ProductTaxon;
use Sylius\Component\Core\Model\ProductTranslation;
use Sylius\Component\Core\Model\ProductVariant;
use Sylius\Component\Core\Model\Shipment;
use Sylius\Component\Core\Model\ShippingMethod;
use Sylius\Component\Order\Model\Adjustment;
use Sylius\Component\Payment\Model\PaymentMethodTranslation;
use Sylius\Component\Shipping\Model\ShippingMethodTranslation;
use Sylius\Component\Taxonomy\Model\TaxonTranslation;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Tests\NFQ\SyliusOmnisendPlugin\Application\Entity\Taxon;
use Tests\NFQ\SyliusOmnisendPlugin\Mock\OrderMock;

class OrderRequestTest extends WebTestCase
{
    use PHPMatcherAssertions;

    /** @var SerializerInterface */
    private $serializer;

    /** @var OrderBuilderDirectorInterface */
    private $director;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->serializer = self::$container->get('serializer');
        $this->director = self::$container->get('nfq_sylius_omnisend_plugin.builder.request.order_director');
    }

    /** @dataProvider data */
    public function testIfFormatValidRequest(OrderMock $data, string $result)
    {
        $this->assertMatchesPattern(
            $result,
            $this->serializer->serialize(
                $this->director->build($data),
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
        $order->setLocaleCode('en');
        $order->setNumber('R0011');
        $order->setCreatedAt(new \DateTime('2012-02-12 12:12:12'));
        $order->setUpdatedAt(new \DateTime('2012-02-12 12:12:12'));
        $order->getOmnisendOrderDetails()->setCartId('111');
        $order->getOmnisendOrderDetails()->setCancelledAt(new \DateTime('2012-02-12 12:12:12'));
        $payment = new Payment();
        $paymentMethod = new PaymentMethod();
        $paymentMethodTrans = new PaymentMethodTranslation();
        $paymentMethodTrans->setLocale('en');
        $paymentMethodTrans->setName('paymentMethodTrans');
        $paymentMethod->addTranslation($paymentMethodTrans);
        $paymentMethod->setCurrentLocale('en');
        $payment->setMethod($paymentMethod);
        $order->addPayment($payment);

        $shipping = new Shipment();
        $shippingMethod = new ShippingMethod();
        $shippingMethodTrans = new ShippingMethodTranslation();
        $shippingMethodTrans->setName('shippingMethodTrans');
        $shippingMethodTrans->setLocale('en');
        $shippingMethod->addTranslation($shippingMethodTrans);
        $shippingMethod->setCurrentLocale('en');
        $shipping->setMethod($shippingMethod);
        $order->addShipment($shipping);

        $customer = new \Sylius\Component\Core\Model\Customer();
        $customer->setEmail('test@nfq.lt');

        $order->setCustomer($customer);
        $order->setNotes('Notes');
        $order->setCurrencyCode('Notes');

        $order->setCurrencyCode('EUR');
        $order->setLocaleCode('en');

        $variant = new ProductVariant();
        $product = new Product();
        $productTranslation = new ProductTranslation();
        $productTranslation->setLocale('en');
        $productTranslation->setSlug('product');
        $product->addTranslation($productTranslation);
        $product->setCurrentLocale('en');
        $variant->setProduct($product);
        $variant->setCurrentLocale('en');
        $product->setCode('product_code');
        $variant->setCode('variant_code');
        $taxon = new Taxon();
        $taxonTranslation = new TaxonTranslation();
        $taxonTranslation->setLocale('en');
        $taxonTranslation->setName('TEST');
        $taxon->setCode('code');
        $taxon->setCurrentLocale('en');
        $taxon->addTranslation($taxonTranslation);
        $productTaxon = new ProductTaxon();
        $productTaxon->setTaxon($taxon);
        $productTaxon->setProduct($product);
        $product->addProductTaxon($productTaxon);
        $taxon1 = new Taxon();
        $taxonTranslation1 = new TaxonTranslation();
        $taxonTranslation1->setLocale('en');
        $taxonTranslation1->setName('TEST');
        $taxon1->setCode('code2');
        $taxon1->setCurrentLocale('en');
        $taxon1->addTranslation($taxonTranslation1);
        $productTaxon1 = new ProductTaxon();
        $productTaxon1->setTaxon($taxon1);
        $productTaxon1->setProduct($product);
        $product->addProductTaxon($productTaxon1);


        $orderItem = new OrderItem();
        $orderItem->setVariant($variant);
        $orderItem->setOrder($order);
        $orderItem->setUnitPrice(20000);
        $orderItem->addUnit(new OrderItemUnit($orderItem));
        $orderItem->setProductName('Name');
        $adjustment1 = new Adjustment();
        $adjustment1->setAmount(-1000);
        $adjustment1->setType(AdjustmentInterface::ORDER_ITEM_PROMOTION_ADJUSTMENT);
        $orderItem->addAdjustment($adjustment1);
        $adjustment2 = new Adjustment();
        $adjustment2->setAmount(2000);
        $adjustment2->setType(AdjustmentInterface::TAX_ADJUSTMENT);
        $orderItem->addAdjustment($adjustment2);
        $adjustment3 = new Adjustment();
        $adjustment3->setAmount(4000);
        $adjustment3->setType(AdjustmentInterface::ORDER_SHIPPING_PROMOTION_ADJUSTMENT);
        $order->addAdjustment($adjustment3);

        $address = new Address();
        $address->setCity('city');
        $address->setStreet('street');
        $address->setPostcode('LT-22223');
        $address->setProvinceName('Province');
        $address->setCountryCode('LT');
        $order->setShippingAddress($address);
        $order->setBillingAddress($address);

        $order->setState('new');
        $order->setPaymentState('paid');


        return [
            'order' => [
                $order,
                <<<JSON
                {
                  "orderID": "@string@",
                  "email": "@string@",
                  "orderNumber": "@integer@",
                  "shippingMethod": "@string@",
                  "orderUrl": "@string@",
                  "currency": "@string@",
                  "orderSum": "@integer@",
                  "subTotalSum": "@integer@",
                  "discountSum": "@integer@",
                  "taxSum": "@integer@",
                  "shippingSum": "@integer@",
                  "createdAt": "@string@",
                  "updatedAt": "@string@",
                  "canceledDate": "@string@",
                  "paymentMethod": "@string@",
                  "paymentStatus": "@string@",
                  "fulfillmentStatus": "@string@",
                  "contactNote": "@string@",
                  "billingAddress": {
                    "country": "@string@",
                    "countryCode": "@string@",
                    "state": "@string@",
                    "city": "@string@",
                    "address": "@string@",
                    "postalCode": "@string@"
                  },
                  "shippingAddress": {
                    "country": "@string@",
                    "countryCode": "@string@",
                    "state": "@string@",
                    "city": "@string@",
                    "address": "@string@",
                    "postalCode": "@string@"
                  },
                  "products": [
                    {
                      "productID": "@string@",
                      "sku": "@string@",
                      "variantID": "@string@",
                      "title": "@string@",
                      "quantity": "@integer@",
                      "price": "@integer@",
                      "discount": "@integer@",
                      "imageUrl": "@string@",
                      "productUrl": "@string@",
                      "tags": "@array@",
                      "categoryIDs": "@array@"
                    }
                  ],
                  "cartID": "@string@"
                }
            JSON,
            ],
        ];
    }
}
