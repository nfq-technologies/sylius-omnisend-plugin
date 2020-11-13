<?php

/*
 * @copyright C UAB NFQ Technologies
 *
 * This Software is the property of NFQ Technologies
 * and is protected by copyright law – it is NOT Freeware.
 *
 * Any unauthorized use of this software without a valid license key
 * is a violation of the license agreement and will be prosecuted by
 * civil and criminal law.
 *
 * Contact UAB NFQ Technologies:
 * E-mail: info@nfq.lt
 * http://www.nfq.lt
 */

declare(strict_types=1);

namespace NFQ\SyliusOmnisendPlugin\Doctrine\ORM;

use Doctrine\ORM\QueryBuilder;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface as BaseTaxonRepositoryInterface;

interface TaxonRepositoryInterface extends BaseTaxonRepositoryInterface
{
    public function getSyncedToOmnisendCount(): int;

    public function findSyncedToOmnisend(int $offset, int $limit): array;

    public function getNotSyncedToOmnisendCount(): int;

    public function findNotSyncedToOmnisend(int $offset, int $limit): array;

    public function getNotSyncedToOmnisendQueryBuilder(string $alias = 't'): QueryBuilder;

    public function getSyncedToOmnisendQueryBuilder(string $alias = 't'): QueryBuilder;
}
