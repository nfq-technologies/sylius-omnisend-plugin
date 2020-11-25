##Orders API

On each taxon action taxon data is pushed to omnisend.
- Order create event is fired after successful checkout.
- Order update events are fired in admin are after order data change.

###Data map
Synced data map:

| OMNISEND FIELD | SYLIUS FIELD  |Description|
|---|---|---|
| orderID | order.omnisendOrderDetails.cartId |_same as cartId_|
| orderNumber      | OrderNumberResolver::resolve(order) | _removes useless symbols_ |
| email  | order.customer.email  |
| firstName  | order.customer.firstName  |
| lastName  | order.customer.lastName  |
| phone  | order.customer.phone  |
| company  | - |
| phone  | order.customer.phone  |
| cartID  | order.omnisendOrderDetails.cartId   |
| shippingMethod  | order.getLastShipment.method.name   |
| trackingCode  | order.trackingCode   |
| courierTitle  | DefaultOrderCourierDataResolver::getCourierTitle   | _should be implemented by implementing OrderCourierDataResolverInterface_ |
| courierUrl  | DefaultOrderCourierDataResolver::getCourierUrl   |_should be implemented by implementing OrderCourierDataResolverInterface_ |
| orderUrl | route: sylius_shop_order_show  ||
| source | not implemented  ||
| tags | not implemented ||
| currency | order.currencyCode  ||
| subTotalSum | SubtotalCalculator::getSubtotal(order) ||
| orderSum | order.total ||
| discountSum | order.orderPromotionTotal ||
| taxSum | order.taxTotal ||
| shippingSum | order.shippingTotal ||
| discountCode | OrderCouponResolverInter::resolve | _returns fixed order.promotionCoupon discount_ |
| discountValue | OrderCouponResolverInter::resolve | _returns fixed order.promotionCoupon discount_ |
| discountType | OrderCouponResolverInter::resolve |  _returns fixed order.promotionCoupon discount_|
| createdAt | order.createAt | |
| updatedAt | order.updatedAt | |
| contactNote | order.notes | |
| paymentMethod | order.lastPayment.method.name | |
| paymentStatus | OrderPaymentStateMapper::map | _can be configurated in plugin config_ |
| canceledDate | order.omnisendOrderDetails.canceledAt  | |
| cancelReason | not implemented | |
| fulfillmentStatus |  OrdertStateMapper::map | _can be configurated in plugin config_ |
| billingAddress |  OrderAddressFactory::create | |
| shippingAddress |  OrderAddressFactory::create | |
| products | [OrderProductFactory::create] ||


###Subscribed actions
All subscribed actions are defined in `OrderSubscriber` class.

```php
    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.order.post_update' => 'onUpdate' #only if order state is not CART
        ];
    }
```

Order data is synced also on order states changes:

```yaml
  sylius_order:
    callbacks:
      after:
        nfq_sylius_omnisend_after_order_state_change:
          on: ["create", "cancel", "fulfill"]
          do: ["@nfq_sylius_omnisend_plugin.event_subscriber.order", "onOrderStateChange"]
          args: ["event", "object"]
          priority: 255

  sylius_payment:
    callbacks:
      after:
        nfq_sylius_omnisend_after_payment_change:
          on: ["create", "process", "authorize", "complete", "fail", "cancel", "refund"]
          do: ["@nfq_sylius_omnisend_plugin.event_subscriber.order", "onPaymentStateChange"]
          args: ["object"]
          priority: 255

  sylius_shipment:
    callbacks:
      after:
        nfq_sylius_omnisend_after_shipment_change:
          on: ["create", "ship", "cancel"]
          do: ["@nfq_sylius_omnisend_plugin.event_subscriber.order", "onShipmentStateChange"]
          args: ["object"]
          priority: 255
```
###Available message bus commands

```php
use NFQ\SyliusOmnisendPlugin\Message\Command\UpdateOrder;
use NFQ\SyliusOmnisendPlugin\Message\Command\UpdateOrderState;
use NFQ\SyliusOmnisendPlugin\Message\Command\CreateOrder;
use NFQ\SyliusOmnisendPlugin\Message\Command\CancelOrder;

     new CreateOrder(
        'orderId',
        'channelCode'
    );
     new UpdateOrder(
        'orderId',
        'channelCode'
    );
     new UpdateOrderState(
        'orderId',
        'channelCode'
    );
     new CancelOrder(
        'orderId',
        'channelCode'
    );
```
