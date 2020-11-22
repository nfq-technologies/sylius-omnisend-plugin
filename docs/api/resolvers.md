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
