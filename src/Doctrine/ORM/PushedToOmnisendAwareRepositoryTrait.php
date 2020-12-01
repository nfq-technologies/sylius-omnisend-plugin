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
    public function findSyncedToOmnisend(?ChannelInterface $channel = null): iterable
    {
        /** @var QueryBuilder $qb */
        $qb = $this->getSyncedToOmnisendQueryBuilder('t', $channel);

        return $qb
            ->getQuery()
            ->iterate();
    }

    public function findNotSyncedToOmnisend(?ChannelInterface $channel = null): iterable
    {
        /** @var QueryBuilder $qb */
        $qb = $this->getNotSyncedToOmnisendQueryBuilder('t', $channel);

        return $qb
            ->getQuery()
            ->iterate();
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
