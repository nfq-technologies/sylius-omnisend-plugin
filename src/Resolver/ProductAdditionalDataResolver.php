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

namespace NFQ\SyliusOmnisendPlugin\Resolver;

use NFQ\SyliusOmnisendPlugin\Utils\DatetimeHelper;
use Sylius\Component\Attribute\Model\AttributeInterface;
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

    public function getCustomFields(ProductInterface $product, string $localeCode = null): ?array
    {
        $attributes = [];

        if (isset($this->attributes['custom_fields'])) {
            foreach ($this->attributes['custom_fields'] as $tagAttrKey) {
                /** @var AttributeValueInterface|null $attributeValue */
                $attributeValue = $product->getAttributeByCodeAndLocale($tagAttrKey, $localeCode);

                if (
                    null !== $attributeValue
                    && null !== $attributeValue->getAttribute()
                ) {
                    $attributes[$tagAttrKey] = (string) $this->resolveAttributeValue($attributeValue);
                }
            }
        }

        return count($attributes) > 0 ? $attributes : null;
    }

    public function getVendor(ProductInterface $product, string $localeCode = null): ?string
    {
        return $this->getAttributeValue('vendor', $product, $localeCode);
    }

    public function getTags(ProductInterface $product, string $localeCode = null): array
    {
        $attributeKey = 'tags';
        $attributeKeyName = $this->attributes[$attributeKey] ?? null;

        if (null === $attributeKeyName) {
            return [];
        }

        /** @var AttributeValueInterface|null $attributeValue */
        $attributeValue = $product->getAttributeByCodeAndLocale($this->attributes[$attributeKey], $localeCode);

        if (
            null !== $attributeValue
            && null !== $attributeValue->getAttribute()
        ) {
            if ($attributeValue->getAttribute()->getStorageType() === AttributeValueInterface::STORAGE_JSON) {
                $config = $attributeValue->getAttribute()->getConfiguration();

                $choices = [];
                foreach ($attributeValue->getValue() as $value) {
                    if (isset($config['choices'][$value][$localeCode])) {
                        $choices[] = $config['choices'][$value][$localeCode];
                    }
                }

                return $choices;
            }

            return [(string) $this->resolveAttributeValue($attributeValue)];
        }

        return [];
    }

    public function getType(ProductInterface $product, string $localeCode = null): ?string
    {
        return $this->getAttributeValue('type', $product, $localeCode);
    }

    protected function getAttributeValue(
        string $attributeKey,
        ProductInterface $product,
        string $localeCode = null
    ): ?string {
        $attributeKeyName = $this->attributes[$attributeKey] ?? null;

        if (null === $attributeKeyName) {
            return null;
        }

        /** @var AttributeValueInterface|null $attribute */
        $attribute = $product->getAttributeByCodeAndLocale($this->attributes[$attributeKey], $localeCode);

        if (
            null !== $attribute
            && null !== $attribute->getAttribute()
        ) {
            return (string) $this->resolveAttributeValue($attribute);
        }

        return null;
    }

    protected function resolveAttributeValue(AttributeValueInterface $attributeValue)
    {
        /** @var AttributeInterface $attribute */
        $attribute = $attributeValue->getAttribute();

        switch ($attribute->getStorageType()) {
            case AttributeValueInterface::STORAGE_BOOLEAN:
            case AttributeValueInterface::STORAGE_FLOAT:
            case AttributeValueInterface::STORAGE_INTEGER:
            case AttributeValueInterface::STORAGE_TEXT:
                return $attributeValue->getValue();
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
