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
