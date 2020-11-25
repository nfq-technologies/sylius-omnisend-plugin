###Resolvers

Some product and order attributes are solved by default services. These services can be configurated:

```yaml
nfq_sylius_omnisend_plugin:
    product_image_resolver: nfq_sylius_omnisend_plugin.resolver.default_product_image
    order_coupon_resolver: nfq_sylius_omnisend_plugin.resolver.default_order_coupon
    product_additional_data_resolver: nfq_sylius_omnisend_plugin.resolver.default_product_additional_data
    product_url_resolver: nfq_sylius_omnisend_plugin.resolver.default_product_url
    product_variant_stock_resolver: nfq_sylius_omnisend_plugin.resolver.default_product_variant_stock
```

- **product_image_resolver** - _return main product image. This plugin takes first image by provided type in the configuration._
```yaml
nfq_sylius_omnisend_plugin:
    product_image:
        type:                 main
        filter:               sylius_shop_product_large_thumbnail
        default_image:        'https://placehold.it/400x30'
```
- **order_coupon_resolver** - _returns formatted coupon data_
```php
namespace NFQ\SyliusOmnisendPlugin\Client\Request\Model;

class OrderCoupon
{
    /** @var string */
    private $code;

    /** @var string */
    private $type;

    /** @var int */
    private $value;
}
```
- **product_additional_data_resolver** - _adds attributes like **vendor**, **type** and **tags** for product requests. This plugin takes these values from product attribute. Acceptable attribute storage type is **text**. These attributes also can be provided in plugin config:
_

```yaml

nfq_sylius_omnisend_plugin:
    product_additional_data_resolver: nfq_sylius_omnisend_plugin.resolver.default_product_additional_data

    product_attributes:
        # Defaults:
        vendor:               omnisend_vendor
        type:                 omnisend_type
        tags:

            # Defaults:
            - omnisend_tag_1
            - omnisend_tag_2
```
- **product_url_resolver** - _resolves product url. This plugin uses `sylius_shop_product_show` router_

- **product_variant_stock_resolver** - _resolves product variants status. should return values :_

```php
    public const STATUS_IN_STOCK = 'inStock';
    public const STATUS_OUT_OF_STOCK = 'outOfStock';
    public const STATUS_NOT_AVAILABLE = 'notAvailable';
```
