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
use Sylius\Component\Core\Model\ChannelInterface;

trait PushedToOmnisendAwareRepositoryTrait
{
    public function getSyncedToOmnisendCount(?ChannelInterface $channel = null): int
    {
        /** @var QueryBuilder $qb */
        $qb = $this->getSyncedToOmnisendQueryBuilder('t', $channel);

        return (int)$qb->select('count(t.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findSyncedToOmnisend(int $offset, int $limit, ?ChannelInterface $channel = null): array
    {
        /** @var QueryBuilder $qb */
        $qb = $this->getSyncedToOmnisendQueryBuilder('t', $channel);

        return $qb
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }

    public function getNotSyncedToOmnisendCount(?ChannelInterface $channel = null): int
    {
        /** @var QueryBuilder $qb */
        $qb = $this->getNotSyncedToOmnisendQueryBuilder('t', $channel);

        return (int)$qb->select('count(t.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findNotSyncedToOmnisend(int $offset, int $limit, ?ChannelInterface $channel = null): array
    {
        /** @var QueryBuilder $qb */
        $qb = $this->getNotSyncedToOmnisendQueryBuilder('t', $channel);

        return $qb
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }

    public function getNotSyncedToOmnisendQueryBuilder(string $alias = 't', ?ChannelInterface $channel = null): QueryBuilder
    {
        /** @var QueryBuilder $qb */
        $qb = $this->createQueryBuilder($alias);

        if (null !== $channel) {
            $this->addChannelFilter($qb, $alias, $channel);
        }

        return $qb->andWhere($qb->expr()->isNull(sprintf('%s.pushedToOmnisend', $alias)));
    }

    public function getSyncedToOmnisendQueryBuilder(string $alias = 't', ?ChannelInterface $channel = null): QueryBuilder
    {
        /** @var QueryBuilder $qb */
        $qb = $this->createQueryBuilder($alias);

        if (null !== $channel) {
            $this->addChannelFilter($qb, $alias, $channel);
        }

        return $qb->andWhere($qb->expr()->isNotNull(sprintf('%s.pushedToOmnisend', $alias)));
    }

    private function addChannelFilter(QueryBuilder $qb, string $alias, ChannelInterface $channel): QueryBuilder
    {
        return $qb
            ->andWhere(sprintf(':channel MEMBER OF %s.channels', $alias))
            ->andWhere(sprintf('%s.enabled = true', $alias))
            ->setParameter('channel', $channel);
    }
}
