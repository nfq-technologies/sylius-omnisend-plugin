##Products API

On each product action product data is pushed to omnisend.

###Data map
Synced data map:

| OMNISEND FIELD | SYLIUS FIELD  |_Description_|
|---|---|---|
| categoryID | taxon.code  | |
| title      | taxon.name  | |
| createdAt  | taxon.code  | |
| updatedAt  | taxon.updatedAt  | |

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
