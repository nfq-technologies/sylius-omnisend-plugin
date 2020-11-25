##Categories API

On all cart actions data should be pushed to Omnisend.
- on product add
- on product remove
- on coupon add
- on item count change
- on checkout step change
- on cart remove (`sylius:remove-expired-carts`) 

###Data map
Synced data map:

| OMNISEND FIELD | SYLIUS FIELD  | Description |
|---|---|---|
| contactID | ContactIdResolver::resolve  ||
| cartID      | order.omnisendOrderDetails.cartId  | _Automaticlly generated on cart create action_ |
| email  | order.customer.email  ||
| phone  | order.customer.phone  ||
| createdAt  | order.createdAt  ||
| updatedAt  | order.updatedAt  ||
| currency  | order.currencyCode  ||
| cartSum  | order.total  ||
| cartRecoveryUrl  | route name: nfq_sylius_omnisend_recover_action  | _Generated url with cartID_|
| products  | [CartProductFactory::create(orderItem)]  ||
| products.cartProductID  | orderItem.id  ||
| products.productID  | orderItem.variant.product.code  ||
| products.variantID  | orderItem.variant.code  ||
| products.sku  |orderItem.variant.product.code  ||
| products.title  | orderItem.productName ||
| products.quantity  | orderItem.quantity  ||
| products.price  | orderItem.total  ||
| products.oldPrice  | products.discount + products.price  ||
| products.discount  | sum of adjustments  | _all orderItem discount addjustment AdjustmentInterface::ORDER_UNIT_PROMOTION_ADJUSTMENT AdjustmentInterface::ORDER_ITEM_PROMOTION_ADJUSTMENT AdjustmentInterface::ORDER_PROMOTION_ADJUSTMENT _|
| products.imageUrl  | ProductImageResolver::resolve(product)  ||
| products.productUrl  | ProductUrlResolver::resolve(product)  ||


###Subscribed actions
All subscribed actions are defined in `CartSubscriber` class.

```php
    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.order_item.post_add' => 'onOrderItemChange',
            'sylius.order_item.post_remove' => 'onOrderItemChange',
            'sylius.order.post_update' => 'onUpdate',
            'sylius.cart_change' => 'onCartChange',
            'sylius.carts.post_remove' => 'onCartsRemove',
            'sylius.order.pre_delete' => 'onCartRemove',
        ];
    }
```

Cart data is synced also on checkout state change:

```yaml

  sylius_order_checkout:
    callbacks:
      after:
        nfq_sylius_omnisend_after_checkout_step_change:
          on: ["address", "select_shipping", "select_payment"]
          do: ['@nfq_sylius_omnisend_plugin.event_subscriber.cart', "updateOrder"]
          args: ["object"]
          priority: 255
```

###Available message bus commands

```php
use NFQ\SyliusOmnisendPlugin\Message\Command\UpdateCart;
use NFQ\SyliusOmnisendPlugin\Message\Command\DeleteCart;

    ##Handles create and update
     new UpdateCart(
        'omnisendCartId',
        'contactID',
        'channelCode'
    );

    new DeleteCart(
        'omnisendCartId',
        'channelCode',
    );
```

###Sync

Sync with Omnisend is only executed then user is logged in or omnisendContactID cookie is added in the browser.
If user is not logged in sync process stars after successfully fulfilled address form.
