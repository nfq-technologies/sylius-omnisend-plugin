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

class ContactIdentifierChannel
{
    /** @var ContactIdentifierChannelValue|null */
    private $email;

    /** @var ContactIdentifierChannelValue|null */
    private $sms;

    public function getEmail(): ?ContactIdentifierChannelValue
    {
        return $this->email;
    }

    public function setEmail(ContactIdentifierChannelValue $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getSms(): ?ContactIdentifierChannelValue
    {
        return $this->sms;
    }

    public function setSms(ContactIdentifierChannelValue $sms): self
    {
        $this->sms = $sms;

        return $this;
    }
}
