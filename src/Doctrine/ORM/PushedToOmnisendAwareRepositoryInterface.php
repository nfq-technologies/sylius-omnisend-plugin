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

namespace NFQ\SyliusOmnisendPlugin\Doctrine\ORM;

use Doctrine\ORM\QueryBuilder;
use Sylius\Component\Core\Model\ChannelInterface;

interface PushedToOmnisendAwareRepositoryInterface
{
    public function findSyncedToOmnisend(?ChannelInterface $channel = null): iterable;

    public function findNotSyncedToOmnisend(?ChannelInterface $channel = null): iterable;

    public function getNotSyncedToOmnisendQueryBuilder(string $alias = 't', ?ChannelInterface $channel = null): QueryBuilder;

    public function getSyncedToOmnisendQueryBuilder(string $alias = 't', ?ChannelInterface $channel = null): QueryBuilder;
}
