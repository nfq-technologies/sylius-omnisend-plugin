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

use NFQ\SyliusOmnisendPlugin\Utils\DatetimeHelper;
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
                ) {
                    $attributes[$tagAttrKey] = $this->resolveAttributeValue($attributeValue);
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

    protected function getAttributeValue(
        string $attributeKey,
        ProductInterface $product,
        string $localeCode = null
    ): ?string
    {
        $attributeKeyName = isset($this->attributes[$attributeKey]) ? $this->attributes[$attributeKey] : null;

        if (null === $attributeKeyName) {
            return null;
        }

        /** @var AttributeValueInterface|null $attribute */
        $attribute = $product->getAttributeByCodeAndLocale($this->attributes[$attributeKey], $localeCode);

        if (
            null !== $attribute
            && null !== $attribute->getAttribute()
        ) {
            return $this->resolveAttributeValue($attribute);
        }

        return null;
    }

    protected function resolveAttributeValue(AttributeValueInterface $attributeValue): ?string
    {
        switch ($attributeValue->getAttribute()->getStorageType()) {
            case AttributeValueInterface::STORAGE_BOOLEAN:
                return (string)(int)$attributeValue->getValue();
            case AttributeValueInterface::STORAGE_FLOAT:
            case AttributeValueInterface::STORAGE_INTEGER:
            case AttributeValueInterface::STORAGE_TEXT:
                return (string)$attributeValue->getValue();
            case AttributeValueInterface::STORAGE_DATETIME:
                if (null !== $attributeValue->getValue()) {
                    return DatetimeHelper::format($attributeValue->getValue());
                }
                break;
            case AttributeValueInterface::STORAGE_DATE:
                if (null !== $attributeValue->getValue()) {
                    return $attributeValue->getValue()->format('Y-m-d');
                }
                break;
            case AttributeValueInterface::STORAGE_JSON:
                if (null !== $attributeValue->getValue()) {
                    return implode(', ', $attributeValue->getValue());
                }
                break;
        }

        return null;
    }
}
