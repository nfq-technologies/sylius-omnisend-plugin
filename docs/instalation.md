
## Installation

1.Run 
```
    composer require nfq/sylius-omnisend-plugin
```

2.Add plugin to your `config/bundles.php` file:

```php
    NFQ\SyliusOmnisendPlugin\NFQSyliusOmnisendPlugin::class => ['all' => true]
```

3.Import routing in your `config/routes.yaml` file:

```yaml
nfq_sylius_omnisend:
    resource: "@NFQSyliusOmnisendPlugin/Resources/config/shop_routing.yml"
```

4.Add required interfaces and use traits

```php
use NFQ\SyliusOmnisendPlugin\Model\ChannelOmnisendTrackingKeyInterface;
use NFQ\SyliusOmnisendPlugin\Model\ChannelOmnisendTrackingKeyTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_channel")
 */
class Channel extends BaseChannel implements ChannelOmnisendTrackingKeyInterface
{
    use ChannelOmnisendTrackingKeyTrait;
}
```
```php
use NFQ\SyliusOmnisendPlugin\Model\ContactAwareInterface;
use NFQ\SyliusOmnisendPlugin\Model\ContactAwareTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_customer")
 */
class Customer extends BaseCustomer implements ContactAwareInterface
{
    use ContactAwareTrait;
}

```

5.Migrations should be generated and executed

```
    bin\console d:m:g
    bin\console d:m:m
```

6.Include services:

```yaml
imports:
    - { resource: "@NFQSyliusOmnisendPlugin/Resources/config/services.yaml" }
```
