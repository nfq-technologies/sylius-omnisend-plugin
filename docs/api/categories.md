##Categories API

On each taxon action taxon data is pushed to omnisend.

###Data map
Synced data map:

| OMNISEND FIELD | SYLIUS FIELD  |
|---|---|
| categoryID | taxon.code  |
| title      | taxon.name  |
| createdAt  | taxon.code  |
| updatedAt  | taxon.updatedAt  |

###Subscribed actions
All subscribed actions are defined in `TaxonSubscriber` class.

```php
    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.taxon.post_create' => 'onUpdate',
            'sylius.taxon.post_update' => 'onUpdate',
            'sylius.taxon.pre_delete' => 'onDelete',
        ];
    }
```

- On taxon create, POST request is sent to Omnisend
- On taxon update, PUT request is sent to Omnisend
- On taxon delete, DELETE request is sent to Omnisend

###Available message bus commands

```php
use NFQ\SyliusOmnisendPlugin\Message\Command\UpdateCategory;
use NFQ\SyliusOmnisendPlugin\Message\Command\DeleteCategory;

    ##Handles create and update
    new UpdateCategory(
        'taxonCode',
        'channelCode',
    );    

    new DeleteCategory(
        'taxonCode',
        'channelCode',
    );
```

###Sync

Initial project data can be synced with [console command](../commands.md)  
```
bin/console nfq:sylius-omnisend:create-batch categories <channelCode> <localeCode>
```
