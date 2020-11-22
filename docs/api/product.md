###PRODUCTS API


####Additional data

Some fields does not have direct value in sylius. To solve issue like this we have prepared ProductAdditionalDataResolver.
This resolver will take product attribute values and will set this values for product.

Futhermore these attributes can by configurated:

```
nfq_sylius_omnisend_plugin.product_attributes: 
    [
        'vendor' => 'omnisend_vendor',
        'type' => 'omnisend_type',
        'tags' => [
            'omnisend_tag_1',
            'omnisend_tag_2'
        ]
    ]
```
