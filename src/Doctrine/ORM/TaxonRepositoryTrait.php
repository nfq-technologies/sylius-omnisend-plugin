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

trait TaxonRepositoryTrait
{
    public function getSyncedToOmnisendCount(): int
    {
        /** @var QueryBuilder $qb */
        $qb = $this->getSyncedToOmnisendQueryBuilder('t');

        return (int)$qb->select('count(t.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findSyncedToOmnisend(int $offset, int $limit): array
    {
        /** @var QueryBuilder $qb */
        $qb = $this->getSyncedToOmnisendQueryBuilder('t');

        return $qb
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }

    public function getNotSyncedToOmnisendCount(): int
    {
        /** @var QueryBuilder $qb */
        $qb = $this->getNotSyncedToOmnisendQueryBuilder('t');

        return (int)$qb->select('count(t.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findNotSyncedToOmnisend(int $offset, int $limit): array
    {
        /** @var QueryBuilder $qb */
        $qb = $this->getNotSyncedToOmnisendQueryBuilder('t');

        return $qb
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }

    public function getNotSyncedToOmnisendQueryBuilder(string $alias = 't'): QueryBuilder
    {
        /** @var QueryBuilder $qb */
        $qb = $this->createQueryBuilder($alias);

        return $qb->andWhere($qb->expr()->isNull(sprintf('%s.pushedToOmnisend', $alias)));
    }

    public function getSyncedToOmnisendQueryBuilder(string $alias = 't'): QueryBuilder
    {
        /** @var QueryBuilder $qb */
        $qb = $this->createQueryBuilder($alias);

        return $qb->andWhere($qb->expr()->isNotNull(sprintf('%s.pushedToOmnisend', $alias)));
    }
}
