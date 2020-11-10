
## Product picker configuration

1. Product picker image can be configurated by changing default config values:
```
      - '%nfq_sylius_omnisend_plugin.product_image.type%' //main
      - '%nfq_sylius_omnisend_plugin.product_image.filter%' //sylius_shop_product_large_thumbnail
      - '%nfq_sylius_omnisend_plugin.product_image.default_image%' //https://placehold.it/400x30
```
- nfq_sylius_omnisend_plugin.product_image.type - product image type (default: main) 
- nfq_sylius_omnisend_plugin.product_image.filter - filter name (default: sylius_shop_product_large_thumbnail)
- nfq_sylius_omnisend_plugin.product_image.default_image - default product image (default: https://placehold.it/400x30)

####Additional fields

Omnisend's not mandatory fields (*vendor, tags*) can by configurated 
by implementing `NFQ\SyliusOmnisendPlugin\Builder\NFQ\SyliusOmnisendPlugin\Model` interface.

For example:
    
<pre>use NFQ\SyliusOmnisendPlugin\Model\ProductPickerAdditionalDataAwareInterface;

class Product extends <i>BaseProduct</i> <b>implements ProductPickerAdditionalDataAwareInterface</b>
{
    <b>public function getOmnisendTags(): array</b>
    {
        return [
            'tag1',
            'tag2',
        ];
    }

    <b>public function getOmnisendVendor(): string</b>
    {
        return 'vendor';
    }
}
</pre>

