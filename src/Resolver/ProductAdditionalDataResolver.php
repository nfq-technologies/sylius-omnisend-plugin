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

use Sylius\Component\Attribute\Model\AttributeValueInterface;
use Sylius\Component\Core\Model\ProductInterface;

class ProductAdditionalDataResolver implements ProductAdditionalDataResolverInterface
{
    /** @var array */
    private $attributes;

    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function getTags(ProductInterface $product, string $localeCode = null): ?array
    {
        $attributes = [];

        if (isset($this->attributes['tags'])) {
            foreach ($this->attributes['tags'] as $tagAttrKey) {
                /** @var AttributeValueInterface|null $attributeValue */
                $attributeValue = $product->getAttributeByCodeAndLocale($tagAttrKey, $localeCode);

                if (
                    null !== $attributeValue
                    && null !== $attributeValue->getAttribute()
                    && $attributeValue->getAttribute()->getStorageType() === 'text'
                ) {
                    $attributes[$tagAttrKey] = $attributeValue->getValue();
                }
            }
        }

        return count($attributes) > 0 ? $attributes : null;
    }

    public function getVendor(ProductInterface $product, string $localeCode = null): ?string
    {
        return $this->getAttributeValue('vendor', $product, $localeCode);
    }

    public function getType(ProductInterface $product, string $localeCode = null): ?string
    {
        return $this->getAttributeValue('type', $product, $localeCode);
    }

    private function getAttributeValue(
        string $attributeKey,
        ProductInterface $product,
        string $localeCode = null
    ): ?string {
        $attributeKeyName = isset($this->attributes[$attributeKey]) ? $this->attributes[$attributeKey] : null;

        if (null === $attributeKeyName) {
            return null;
        }

        /** @var AttributeValueInterface|null $attribute */
        $attribute = $product->getAttributeByCodeAndLocale($this->attributes[$attributeKey], $localeCode);

        if (
            null !== $attribute
            && null !== $attribute->getAttribute()
            && $attribute->getAttribute()->getStorageType() === 'text'
        ) {
            return $attribute->getValue();
        }

        return null;
    }
}
