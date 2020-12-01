##Contact API

Customer data is pushed to Omnisend on new user register action. 
Updated data is pushed to Omnisend on user default address change.
On each admin customer action, updated data is pushed to Omnisend.

###Data map
Synced data map:

| OMNISEND FIELD | SYLIUS FIELD  |_Description_|
|---|---|---|
| identifiers |  | _Data is built according by customer.isSubscribedToNewsletter and customer.email and plugin configuration values like sendWelcomeMessage fields_|
| contactID | customer.omnisendContactID | |
| createdAt | customer.createdAt | |
| firstName | customer.firstName | |
| lastName | customer.lastName | |
| tags | DefaultCustomerAdditionalDataResolver::getTags | _By default returns null value. This resolver can be changed in plugin config_ |
| country | customer.defaultAddress.country | |
| countryCode | customer.defaultAddress.countryCode | |
| state | customer.defaultAddress.state | |
| city | customer.defaultAddress.city | |
| address | customer.defaultAddress.street | |
| postalCode | customer.gender | |
| birthdate | customer.birthdate | |
| customProperties | DefaultCustomerAdditionalDataResolver::customProperties |_By default returns null value. This resolver can be changed in plugin config_  |

###Subscribed actions
All subscribed actions are defined in `TaxonSubscriber` class.

```php
   public static function getSubscribedEvents(): array
    {
        return [
            'sylius.customer.post_create' => 'onUpdate',
            'sylius.customer.post_register' => 'onRegister',
            'sylius.customer.post_update' => 'onUpdate',
            'sylius.address.post_update' => 'onAddressUpdate',
            'sylius.address.post_create' => 'onAddressUpdate',
        ];
    }
```

- On customer register, POST request is sent to Omnisend
- On customer data change, PATCH request is sent to Omnisend

###Available message bus commands

```php
use NFQ\SyliusOmnisendPlugin\Message\Command\UpdateContact;

    #handles create and update
    new UpdateContact(
        'customerId',
        'channelCode'
    );
```

###OmnisendContactId

This cookie is required for omnisend to indentify current shop user. If this cookie is not provided, Omnisend uses customer email in checkout.

Cookie `omnisendContactId` is added to the browser:
- On successful user registration action.
- On cart recovery action.
- On user login.
