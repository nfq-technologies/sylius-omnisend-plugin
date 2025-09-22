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

namespace Tests\NFQ\SyliusOmnisendPlugin\Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use NFQ\SyliusOmnisendPlugin\Model\ContactAwareInterface;
use NFQ\SyliusOmnisendPlugin\Model\ContactAwareTrait;
use Sylius\Component\Core\Model\Customer as BaseCustomer;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_customer")
 */
class Customer extends BaseCustomer implements ContactAwareInterface
{
    use ContactAwareTrait;

    private ?bool $subscribedToSms = null;

    private ?bool $subscribedToEmail = null;

    public function isSubscribedToSms(): bool
    {
        return $this->subscribedToSms === true;
    }

    public function isSubscribedToEmail(): bool
    {
        return $this->subscribedToEmail === true;
    }

    public function setSubscribedToSms(?bool $subscribedToSms): void
    {
        $this->subscribedToSms = $subscribedToSms;
    }

    public function getSubscribedToEmail(): ?bool
    {
        return $this->subscribedToEmail;
    }

    public function getSubscribedToSms(): ?bool
    {
        return $this->subscribedToSms;
    }

    public function setSubscribedToEmail(?bool $subscribedToEmail): void
    {
        $this->subscribedToEmail = $subscribedToEmail;
        // for BC
        $this->subscribedToNewsletter = $subscribedToEmail === true;
    }

    /** @deprecated use ::setSubscribedToEmail() instead */
    public function setSubscribedToNewsletter(bool $subscribedToNewsletter): void
    {
        $this->subscribedToEmail = $subscribedToNewsletter;
        $this->subscribedToNewsletter = $subscribedToNewsletter;
    }

    /** @deprecated use ::getSubscribedToEmail() instead */
    public function isSubscribedToNewsletter(): bool
    {
        return $this->isSubscribedToEmail();
    }
}
