<?php

/*
 * This file is part of the NFQ package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\NFQ\SyliusOmnisendPlugin\Application\Repository;

use NFQ\SyliusOmnisendPlugin\Doctrine\ORM\ProductRepositoryInterface;
use NFQ\SyliusOmnisendPlugin\Doctrine\ORM\PushedToOmnisendAwareRepositoryTrait;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\ProductRepository as BaseRepository;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    use PushedToOmnisendAwareRepositoryTrait;
}
