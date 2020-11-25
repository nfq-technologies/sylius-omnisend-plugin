###State mapper

Omnisend has custom order and payment states. By default, this plugin provides Sylius and Omnisend states map. 
But these states can be overwritten by changing plugin config values.

#####Order States

Default order states mapped in file ```OrderStateMapper.php```
```php
use NFQ\SyliusOmnisendPlugin\Builder\Request\Constants\OrderFulfillmentStatus;
use Sylius\Component\Core\Model\OrderInterface;

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

#####Payment states

Default order payment states mapped in file `OrderPaymentStateMapper.php`
```php
use NFQ\SyliusOmnisendPlugin\Builder\Request\Constants\OrderPaymentStatus;
use Sylius\Component\Core\OrderPaymentStates;

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
