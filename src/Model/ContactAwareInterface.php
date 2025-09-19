<?php

declare(strict_types=1);

/*
 * This file is part of the NFQ package.
 *
 * (c) Nfq Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NFQ\SyliusOmnisendPlugin\Model;

use Sylius\Component\Core\Model\CustomerInterface as BaseCustomerInterface;

interface ContactAwareInterface extends BaseCustomerInterface
{
    public function getOmnisendContactId(): ?string;

    public function setOmnisendContactId(string $omnisendTrackingKey): void;

    public function isSubscribedToSms(): bool;

    public function isSubscribedToEmail(): bool;

    public function getSubscribedToEmail(): ?bool;

    public function getSubscribedToSms(): ?bool;
}
