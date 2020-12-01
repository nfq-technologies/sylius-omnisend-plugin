<?php

/*
 * This file is part of the NFQ package.
 *
 * (c) Nfq Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\NFQ\SyliusOmnisendPlugin\Application\Repository;

use NFQ\SyliusOmnisendPlugin\Doctrine\ORM\PushedToOmnisendAwareRepositoryTrait;
use Sylius\Bundle\TaxonomyBundle\Doctrine\ORM\TaxonRepository as BaseRepository;
use NFQ\SyliusOmnisendPlugin\Doctrine\ORM\TaxonRepositoryInterface;

class TaxonRepository extends BaseRepository implements TaxonRepositoryInterface
{
    use PushedToOmnisendAwareRepositoryTrait;
}
