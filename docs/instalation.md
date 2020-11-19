
## Installation

1.Run 
```
    composer require nfq/sylius-omnisend-plugin
```

2.Add plugin to your `config/bundles.php` file:

```
    NFQ\SyliusOmnisendPlugin\NFQSyliusOmnisendPlugin::class => ['all' => true],
```

3.Import routing in your `config/routes.yaml` file:

```
nfq_sylius_omnisend:
    resource: "@NFQSyliusOmnisendPlugin/Resources/config/shop_routing.yml"
```

4.Add required interfaces and use traits

<pre>

<b>use NFQ\SyliusOmnisendPlugin\Model\ChannelOmnisendTrackingKeyInterface;
use NFQ\SyliusOmnisendPlugin\Model\ChannelOmnisendTrackingKeyTrait;</b>


class Channel extends BaseChannel <b>implements OmnisendTrackingKeyAwareInterface</b>
{
    <b>use OmnisendTrackingKeyAwareTrait;</b>
}
</pre>

5.Migrations should be generated:

```
    bin/console doctrine:migrations:diff
    bin/console doctrine:migrations:migrate
```

6.Include services:

```
imports:
    - { resource: "@NFQSyliusOmnisendPlugin/Resources/config/services.yaml" }
```
