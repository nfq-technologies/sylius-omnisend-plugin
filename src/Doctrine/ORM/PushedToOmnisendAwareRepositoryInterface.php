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

interface PushedToOmnisendAwareRepositoryInterface
{
    public function getSyncedToOmnisendCount(?ChannelInterface $channel = null): int;

    public function findSyncedToOmnisend(int $offset, int $limit, ?ChannelInterface $channel = null): array;

    public function getNotSyncedToOmnisendCount(?ChannelInterface $channel = null): int;

    public function findNotSyncedToOmnisend(int $offset, int $limit, ?ChannelInterface $channel = null): array;

    public function getNotSyncedToOmnisendQueryBuilder(string $alias = 't', ?ChannelInterface $channel = null): QueryBuilder;

    public function getSyncedToOmnisendQueryBuilder(string $alias = 't', ?ChannelInterface $channel = null): QueryBuilder;
}
