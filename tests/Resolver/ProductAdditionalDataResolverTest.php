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

    public function testIfResolvesVendor()
    {
        $product = new Product();
        $product->setCurrentLocale('en');
        $attribute = new ProductAttribute();
        $attribute->setCode('omnisend_vendor');
        $attributeValue = new ProductAttributeValue();
        $attributeValue->setLocaleCode('en');
        $attributeValue->setAttribute($attribute);
        $attribute->setStorageType('text');
        $attributeValue->setValue('VALUE');
        $attributeValue->setProduct($product);
        $product->addAttribute($attributeValue);

        $result = $this->resolver->getVendor($product);

        $this->assertEquals($result, 'VALUE');
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

        $result = $this->resolver->getTags($product);

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
