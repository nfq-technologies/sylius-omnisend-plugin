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

use stdClass;

trait ContactAwareTrait
{
    /**
     * @var string|null
     *
     * @ORM\Column(type="string", name="omnisend_contact_id", length=32, nullable=true)
     */
    private $omnisendContactId;

    public function getOmnisendContactId(): ?string
    {
        return $this->omnisendContactId;
    }

    public function setOmnisendContactId(string $omnisendContactId): void
    {
        $this->omnisendContactId = $omnisendContactId;
    }

    public function getOmnisendCustomProperties(): ?stdClass
    {
        return null;
    }

    public function getOmnisendTags(): ?array
    {
        return null;
    }

    public function isSubscribedToSMS(): bool
    {
        return false;
    }
}
