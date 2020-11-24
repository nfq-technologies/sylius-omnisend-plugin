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
