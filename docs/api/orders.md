###ORDERS API

####Order coupon resolver:

If current coupon resolver `DefaultOrderCouponResolver` does not meet your requirements, you can add your own by implementing interface : `OrderCouponResolverInterface` and adding tag `nfq_sylius_omnisend_plugin.resolver.order_coupon`

For example:
```php
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\OrderCoupon;
use NFQ\SyliusOmnisendPlugin\Model\OrderInterface;
use NFQ\SyliusOmnisendPlugin\Resolver\OrderCouponResolverInterface;

class CustomResolver implements OrderCouponResolverInterface
{
    public function resolve(OrderInterface $order): ?OrderCoupon
    {
        //TODO implement magic
    }
}
```

```yaml
  my_custom_resolver:
    class: CustomResolver
    tags:
      - { name: nfq_sylius_omnisend_plugin.resolver.order_coupon }
```

####States mapper:

#####Order

Default order states mapped in file ```OrderStateMapper.php```
```php
    const DEFAULT_MAP = [
        OrderInterface::STATE_CART => OrderFulfillmentStatus::UNFULFILL,
        OrderInterface::STATE_NEW => OrderFulfillmentStatus::NEW,
        OrderInterface::STATE_CANCELLED => OrderFulfillmentStatus::UNFULFILL,
        OrderInterface::STATE_FULFILLED => OrderFulfillmentStatus::FULFILL
    ];
```

If this mapping does not meet your requirements, it can be changed in plugin config:

```yaml
nfq_sylius_omnisend_plugin:
  order_states:
    customOrderState: omnisendState
```

#####Payment

Default order payment states mapped in file `OrderPaymentStateMapper.php`
```php
    const DEFAULT_MAP = [
        OrderPaymentStates::STATE_CART => OrderPaymentStatus::AWAITING,
        OrderPaymentStates::STATE_CANCELLED => OrderPaymentStatus::VOID,
        OrderPaymentStates::STATE_AWAITING_PAYMENT => OrderPaymentStatus::AWAITING,
        OrderPaymentStates::STATE_PARTIALLY_PAID => OrderPaymentStatus::PARTIALLY_PAY,
        OrderPaymentStates::STATE_PAID => OrderPaymentStatus::PAY,
        OrderPaymentStates::STATE_AUTHORIZED => OrderPaymentStatus::AWAITING,
        OrderPaymentStates::STATE_PARTIALLY_AUTHORIZED => OrderPaymentStatus::AWAITING,
        OrderPaymentStates::STATE_PARTIALLY_REFUNDED => OrderPaymentStatus::PARTIALLY_REFUND,
        OrderPaymentStates::STATE_REFUNDED => OrderPaymentStatus::REFUND,
    ];
```

If this mapping does not meet your requirements, it can be changed in plugin config:

```yaml
nfq_sylius_omnisend_plugin:
  payment_states:
    customPaymentState: omnisendState
```
