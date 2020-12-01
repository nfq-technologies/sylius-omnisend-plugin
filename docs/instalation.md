## Installation

####1. Run 
```
    composer require nfq/sylius-omnisend-plugin
```

####2. Add plugin to your `config/bundles.php` file:

```php
    NFQ\SyliusOmnisendPlugin\NFQSyliusOmnisendPlugin::class => ['all' => true],
```

####3. Import routing in your `config/routes.yaml` file:

```yaml
# config/routes.yaml
nfq_sylius_omnisend:
    resource: "@NFQSyliusOmnisendPlugin/Resources/config/shop_routing.yml"
    #prefix: /{_locale} #not required
    #requirements:
    #    _locale: ^[a-z]{2}(?:_[A-Z]{2})?$ #not required

nfq_sylius_omnisend_admin:
    resource: "@NFQSyliusOmnisendPlugin/Resources/config/admin_routing.yml"


```

####4. Add required interfaces and use traits

####Entities

- Channel
```php
use Doctrine\ORM\Mapping as ORM;
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
- Customer
```php
use Doctrine\ORM\Mapping as ORM;
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
- Taxon
```php
use Doctrine\ORM\Mapping as ORM;
use NFQ\SyliusOmnisendPlugin\Model\PushedToOmnisendAwareTrait;
use Sylius\Component\Core\Model\Taxon as BaseTaxon;
use NFQ\SyliusOmnisendPlugin\Model\TaxonInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_taxon")
 */
class Taxon extends BaseTaxon implements TaxonInterface
{
    use PushedToOmnisendAwareTrait;
}
```
- Order
```php
use Doctrine\ORM\Mapping as ORM;
use NFQ\SyliusOmnisendPlugin\Model\OmnisendOrderDetailsAwareTrait;
use NFQ\SyliusOmnisendPlugin\Model\OrderInterface;
use Sylius\Component\Core\Model\Order as BaseOrder;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_order")
 */
class Order extends BaseOrder implements OrderInterface
{
    use OmnisendOrderDetailsAwareTrait;

    use OmnisendOrderDetailsAwareTrait {
        OmnisendOrderDetailsAwareTrait::__construct as private omnisendOrderDetailsConstruct;
    }

    public function __construct()
    {
        parent::__construct();
        $this->omnisendOrderDetailsConstruct();
    }
}
```
- Product
```php
use Doctrine\ORM\Mapping as ORM;
use NFQ\SyliusOmnisendPlugin\Model\ProductInterface;
use NFQ\SyliusOmnisendPlugin\Model\ProductTrait;
use Sylius\Component\Core\Model\Product as BaseProduct;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_product")
 */
class Product extends BaseProduct implements ProductInterface
{
    use ProductTrait;
}

```

####Repositories
- ProductRepository
```php
use NFQ\SyliusOmnisendPlugin\Doctrine\ORM\ProductRepositoryInterface;
use NFQ\SyliusOmnisendPlugin\Doctrine\ORM\PushedToOmnisendAwareRepositoryTrait;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\ProductRepository as BaseRepository;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    use PushedToOmnisendAwareRepositoryTrait;
}
```
- TaxonRepository
```php
use NFQ\SyliusOmnisendPlugin\Doctrine\ORM\PushedToOmnisendAwareRepositoryTrait;
use Sylius\Bundle\TaxonomyBundle\Doctrine\ORM\TaxonRepository as BaseRepository;
use NFQ\SyliusOmnisendPlugin\Doctrine\ORM\TaxonRepositoryInterface;

class TaxonRepository extends BaseRepository implements TaxonRepositoryInterface
{
    use PushedToOmnisendAwareRepositoryTrait;
}
```

! Update Sylius resources config in `config/_sylius.yaml`

####5. Migrations should be generated and executed.

```
    bin/console doctrine:migrations:diff
    bin/console doctrine:migrations:migrate
```

####6. Include config:

```yaml
#config/packages/_sylius.yaml
imports:
    - { resource: "@NFQSyliusOmnisendPlugin/Resources/config/config.yaml" }
```

####7. [Configure plugin and add tracking keys in admin area.](configuration.md)
