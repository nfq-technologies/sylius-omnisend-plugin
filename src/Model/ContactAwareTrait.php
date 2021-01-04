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

namespace NFQ\SyliusOmnisendPlugin\Model;

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

    public function getOmnisendCustomProperties(): ?array
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
