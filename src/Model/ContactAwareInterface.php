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

namespace NFQ\SyliusOmnisendPlugin\Model;

use stdClass;
use Sylius\Component\Core\Model\CustomerInterface as BaseCustomerInterface;

interface ContactAwareInterface extends BaseCustomerInterface
{
    public function getOmnisendContactId(): ?string;

    public function setOmnisendContactId(string $omnisendTrackingKey): void;

    public function getOmnisendCustomProperties(): ?stdClass;

    public function getOmnisendTags(): ?array;

    public function isSubscribedToSMS(): bool;
}
