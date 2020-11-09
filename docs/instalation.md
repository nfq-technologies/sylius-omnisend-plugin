
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
