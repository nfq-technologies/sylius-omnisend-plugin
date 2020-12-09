###Resolvers

Some product and order attributes are solved by default services. These services can be configurated:

```yaml
nfq_sylius_omnisend:
    product_image_resolver: nfq_sylius_omnisend_plugin.resolver.default_product_image
    order_coupon_resolver: nfq_sylius_omnisend_plugin.resolver.default_order_coupon
    courier_data_resolver: nfq_sylius_omnisend_plugin.resolver.default_courier_data_resolver
    product_additional_data_resolver: nfq_sylius_omnisend_plugin.resolver.default_product_additional_data
    product_url_resolver: nfq_sylius_omnisend_plugin.resolver.default_product_url
    product_variant_stock_resolver: nfq_sylius_omnisend_plugin.resolver.default_product_variant_stock
    customer_additional_data: nfq_sylius_omnisend_plugin.resolver.default_customer_additional_data
```

- **product_image_resolver** - _return main product image. This plugin takes first image by provided type in the configuration._
```yaml
nfq_sylius_omnisend:
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
- **product_additional_data_resolver** - _adds attributes like **vendor**, **type**, **tags**, **custom_fields** in product requests. These attributes also can be configurated in plugin config:_

```yaml

nfq_sylius_omnisend:
    product_additional_data_resolver: nfq_sylius_omnisend_plugin.resolver.default_product_additional_data

    product_attributes:
        vendor: omnisend_vendor
        type: omnisend_type
        tags: omnisend_tags
        custom_fields:
            - omnisend_custom_field_1
            - omnisend_custom_field_2
```
- **product_url_resolver** - _resolves product url. This plugin uses `sylius_shop_product_show` router_

- **product_variant_stock_resolver** - _resolves product variants status. should return values :_

```php
    public const STATUS_IN_STOCK = 'inStock';
    public const STATUS_OUT_OF_STOCK = 'outOfStock';
    public const STATUS_NOT_AVAILABLE = 'notAvailable';
```
- **courier_data_resolver** - _returns empty order courier data. This default resolver should be overwritten by your project custom courier implementation._
- **customer_additional_data** - _returns empty customer additional tags. This default resolver should be overwritten by your project custom implementation._
