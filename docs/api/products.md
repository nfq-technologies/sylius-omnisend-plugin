##Products API

On each product action product data is pushed to omnisend.

###Data map
Synced data map:

| OMNISEND FIELD | SYLIUS FIELD  |_Description_|
|---|---|---|
| productID | product.code  | |
| title      | product.name  | |
| status  | ProductBuilder::addStockStatus(product)  | _Returns inStock value if at least one variant has stock_|
| description  | product.description  | |
| currency  | channel.baseCurrency.code  | |
| productUrl  | ProductUrlResolver.resolve(product)  | _Returns formatted url by route sylius_shop_product_show_ |
| vendor  | ProductAdditionalDataResolver.getVendor(product)  | _Returns value from omnisend_vendor attribute_ |
| type  | ProductAdditionalDataResolver.getType(type)  | _Returns value from omnisend_type attribute_ |
| createdAt  | product.createdAt | |
| updatedAt  | product.updatedAt | |
| tags  | ProductAdditionalDataResolver.getTags(product)  | _Returns array of attribute values. We strongly recommend to have multivalue select type attribute for this_ |
| categoryIDs  | product.taxons  | _Returns product taxons codes_ |
| images  | ProductImageFactory::create | _Returns first 10 product images by provided type in config. If type is not provided or null returns first 10 product images_ |
| images.imageID  | productImage.id | |
| images.url  | productImage.path | _adds provided filter for image_ |
| images.isDefault  |  | _First image_|
| images.variantIDs  | productImage.productVariants | _if has variants adds them. Adds all product variants by default_ |
| variants  | product.createdAt | |
| variants.variantID  | productVariant.code | |
| variants.title  | productVariant.name | |
| variants.sku  | productVariant.code | |
| variants.status  | ProductVariantStockResolver::resolve(productVariant) | |
| variants.price  | productVariant.price | |
| variants.oldPrice  | productVariant.oldPrice | _if oldPrice is bigger than price_ |
| variants.productUrl  | ProductUrlResolver.resolve(productVariant.product)| |
| variants.imageID  | - | |
| variants.customFields  | ProductAdditionalDataResolver.getCustomFields(productVariant.product) | |

###Subscribed actions
All subscribed actions are defined in `TaxonSubscriber` class.

```php
    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.product.post_create' => 'onUpdate',
            'sylius.product.post_update' => 'onUpdate',
            'sylius.product.pre_delete' => 'onDelete',
        ];
    }
```

- On product create, POST request is sent to Omnisend
- On product update, PUT request is sent to Omnisend
- On product delete, DELETE request is sent to Omnisend

###Available message bus commands

```php
use NFQ\SyliusOmnisendPlugin\Message\Command\UpdateProduct;
use NFQ\SyliusOmnisendPlugin\Message\Command\DeleteProduct;

    #handles create and update
    new UpdateProduct(
        "productCode",
        "channelCode",
        "localeCode"
    );

    new DeleteProduct(
        'productCode',
        'channelCode',
    );
```

###Sync

Initial project data can be synced to Omnisend with [console command](../commands.md)  
```
bin/console nfq:sylius-omnisend:create-batch products <channelCode> <localeCode>
```
