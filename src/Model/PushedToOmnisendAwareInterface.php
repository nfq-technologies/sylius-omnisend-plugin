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

namespace NFQ\SyliusOmnisendPlugin\Model;

use DateTimeInterface;

interface PushedToOmnisendAwareInterface
{
    /**
     * Returns true if this entity ever was pushed/synced to omnisend
     */
    public function isPushedToOmnisend(): bool;

    /**
     * Returns the last time this entity was pushed/synced to omnisend
     * Returns null if the entity has never been synced
     */
    public function getPushedToOmnisend(): ?DateTimeInterface;

    /**
     * If null is given the method will set the last omnisend sync to 'now'
     */
    public function setPushedToOmnisend(DateTimeInterface $dateTime = null): void;
}
