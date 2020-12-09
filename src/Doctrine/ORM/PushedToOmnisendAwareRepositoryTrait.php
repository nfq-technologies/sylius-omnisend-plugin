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
