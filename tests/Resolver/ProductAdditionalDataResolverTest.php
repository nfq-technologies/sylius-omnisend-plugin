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

namespace Tests\NFQ\SyliusOmnisendPlugin\Resolver;

use NFQ\SyliusOmnisendPlugin\Resolver\ProductAdditionalDataResolver;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Attribute\Model\AttributeValueInterface;
use Sylius\Component\Core\Model\Product;
use Sylius\Component\Product\Model\ProductAttribute;
use Sylius\Component\Product\Model\ProductAttributeValue;

class ProductAdditionalDataResolverTest extends TestCase
{
    /** @var ProductAdditionalDataResolver */
    private $resolver;

    public function setUp(): void
    {
        $this->resolver = new ProductAdditionalDataResolver(
            [
                'type' => 'omnisend_type',
                'tags' => ['tag_1', 'tag_2'],
                'vendor' => 'omnisend_vendor',
            ]
        );
    }

    /** @dataProvider attributeData */
    public function testIfResolvesVendor(ProductAttributeValue $attributeValue, string $attribute)
    {
        $product = new Product();
        $product->setCurrentLocale('en');
        $attributeValue->getAttribute()->setCode('omnisend_vendor');
        $attributeValue->setProduct($product);
        $product->addAttribute($attributeValue);

        $result = $this->resolver->getVendor($product);

        $this->assertEquals($result, $attribute);
    }

    public function attributeData()
    {
        $attribute1 = new ProductAttribute();
        $attributeValue1 = new ProductAttributeValue();
        $attributeValue1->setLocaleCode('en');
        $attributeValue1->setAttribute($attribute1);
        $attribute1->setStorageType(AttributeValueInterface::STORAGE_JSON);
        $attributeValue1->setValue(['value', 'value2']);

        $attribute2 = new ProductAttribute();
        $attributeValue2 = new ProductAttributeValue();
        $attributeValue2->setLocaleCode('en');
        $attributeValue2->setAttribute($attribute2);
        $attribute2->setStorageType(AttributeValueInterface::STORAGE_DATETIME);
        $attributeValue2->setValue(new \DateTime('2012-02-12 12:12:12'));

        $attribute5 = new ProductAttribute();
        $attributeValue5 = new ProductAttributeValue();
        $attributeValue5->setLocaleCode('en');
        $attributeValue5->setAttribute($attribute5);
        $attribute5->setStorageType(AttributeValueInterface::STORAGE_DATE);
        $attributeValue5->setValue(new \DateTime('2012-02-12'));

        $attribute3 = new ProductAttribute();
        $attributeValue3 = new ProductAttributeValue();
        $attributeValue3->setLocaleCode('en');
        $attributeValue3->setAttribute($attribute3);
        $attribute3->setStorageType(AttributeValueInterface::STORAGE_FLOAT);
        $attributeValue3->setValue(3.3);

        $attribute4 = new ProductAttribute();
        $attributeValue4 = new ProductAttributeValue();
        $attributeValue4->setLocaleCode('en');
        $attributeValue4->setAttribute($attribute4);
        $attribute4->setStorageType(AttributeValueInterface::STORAGE_BOOLEAN);
        $attributeValue4->setValue(false);

        return [
            'json' => [
                $attributeValue1,
                'value, value2'
            ],
            'datetime' => [
                $attributeValue2,
                '2012-02-12T12:12:12+00:00'
            ],
            'date' => [
                $attributeValue5,
                '2012-02-12'
            ],
            'bool' => [
                $attributeValue4,
                false
            ],
            'float' => [
                $attributeValue3,
                3.3
            ],
        ];
    }

    public function testIfResolvesTags()
    {
        $product = new Product();
        $product->setCurrentLocale('en');

        $attribute = new ProductAttribute();
        $attribute->setCode('tag_1');
        $attributeValue = new ProductAttributeValue();
        $attributeValue->setLocaleCode('en');
        $attributeValue->setAttribute($attribute);
        $attribute->setStorageType('text');
        $attributeValue->setValue('VALUE');
        $attributeValue->setProduct($product);
        $product->addAttribute($attributeValue);

        $attribute2 = new ProductAttribute();
        $attribute2->setCode('tag_2');
        $attributeValue2 = new ProductAttributeValue();
        $attributeValue2->setLocaleCode('en');
        $attributeValue2->setAttribute($attribute2);
        $attribute2->setStorageType('text');
        $attributeValue2->setValue('VALUE_2');
        $attributeValue2->setProduct($product);
        $product->addAttribute($attributeValue2);

        $result = $this->resolver->getCustomFields($product);

        $this->assertEquals(
            $result,
            [
                'tag_1' => 'VALUE',
                'tag_2' => 'VALUE_2',
            ]
        );
    }

    public function testIfDoNotResolvesVendor()
    {
        $product = new Product();
        $product->setCurrentLocale('en');
        $attribute = new ProductAttribute();
        $attribute->setStorageType('text');
        $attribute->setCode('omnisend_not_vendor');
        $attributeValue = new ProductAttributeValue();
        $attributeValue->setLocaleCode('en');
        $attributeValue->setAttribute($attribute);
        $attributeValue->setValue('VALUE');
        $attributeValue->setProduct($product);
        $product->addAttribute($attributeValue);

        $result = $this->resolver->getVendor($product);

        $this->assertEquals($result, null);
    }
}
