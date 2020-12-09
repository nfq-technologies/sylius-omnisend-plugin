####Custom product tags

Some Omnisend product's fields does not have direct value in Sylius. To solve issue like this we have prepared `ProductAdditionalDataResolver`.
This resolver will take product attribute values and will set this values for product.

Futhermore these attributes can by configurated in plugin configuration file:

```yaml
nfq_sylius_omnisend:
    product_attributes:
        #Default attribute codes:
        vendor:               omnisend_vendor
        type:                 omnisend_type
        tags:

            #Default attribute codes:
            - omnisend_tag_1
            - omnisend_tag_2
```

If provided custom tags resolver does not meet your requirements you can change it in plugin config.
```yaml
nfq_sylius_omnisend:
    product_additional_data_resolver: nfq_sylius_omnisend_plugin.resolver.default_product_additional_data
```
