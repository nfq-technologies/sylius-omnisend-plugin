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

namespace NFQ\SyliusOmnisendPlugin\Client\Request\Model;

class ContactIdentifierChannelValue
{
    public const SUBSCRIBED = 'subscribed';

    public const UNSUBSCRIBED = 'unsubscribed';

    public const NON_SUBSCRIBED = 'nonSubscribed';

    /** @var string */
    private $status;

    /** @var string|null */
    private $statusDate;

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getStatusDate(): ?string
    {
        return $this->statusDate;
    }

    public function setStatusDate(?string $statusDate): self
    {
        $this->statusDate = $statusDate;

        return $this;
    }
}
