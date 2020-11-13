
## Installation

1.Run 
```
    composer require nfq/sylius-omnisend-plugin
```

2.Add plugin to your `config/bundles.php` file:

```php
    NFQ\SyliusOmnisendPlugin\NFQSyliusOmnisendPlugin::class => ['all' => true],
```

3.Import routing in your `config/routes.yaml` file:

```yaml
nfq_sylius_omnisend:
    resource: "@NFQSyliusOmnisendPlugin/Resources/config/shop_routing.yml"
```

4.Add required interfaces and use traits

####Entities
```php
use NFQ\SyliusOmnisendPlugin\Model\ChannelOmnisendTrackingAwareInterface;
use NFQ\SyliusOmnisendPlugin\Model\ChannelOmnisendTrackingAwareTrait;
use Sylius\Component\Core\Model\Channel as BaseChannel;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_channel")
 */
class Channel extends BaseChannel implements ChannelOmnisendTrackingAwareInterface
{
    use ChannelOmnisendTrackingAwareTrait;
}
```
```php
use Sylius\Component\Core\Model\Customer as BaseCustomer;
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
```php
use Sylius\Component\Core\Model\Taxon as BaseTaxon;
use NFQ\SyliusOmnisendPlugin\Model\TaxonInterface;
use NFQ\SyliusOmnisendPlugin\Model\TaxonTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_taxon")
 */
class Taxon extends BaseTaxon implements TaxonInterface
{
    use TaxonTrait;
}
```
####Repositories

```php
use Sylius\Bundle\TaxonomyBundle\Doctrine\ORM\TaxonRepository as BaseRepository;
use NFQ\SyliusOmnisendPlugin\Doctrine\ORM\TaxonRepositoryInterface;
use NFQ\SyliusOmnisendPlugin\Doctrine\ORM\TaxonRepositoryTrait;

class TaxonRepository extends BaseRepository implements TaxonRepositoryInterface
{
    use TaxonRepositoryTrait;
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
