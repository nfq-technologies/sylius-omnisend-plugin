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

namespace NFQ\SyliusOmnisendPlugin\Client\Response\Model;

class ContactSuccessList
{
    /** @var ContactSuccess[] */
    private array $contacts = [];

    /** @return ContactSuccess[] */
    public function getContacts(): array
    {
        return $this->contacts;
    }

    public function addContact(ContactSuccess $contact): void
    {
        $this->contacts[] = $contact;
    }

    /** @param ContactSuccess[] $contacts */
    public function setContacts(array $contacts): void
    {
        $this->contacts = $contacts;
    }

    /**
     * @phpstan-assert-if-true array{} $this->contacts
     * @phpstan-assert-if-false non-empty-array<ContactSuccess> $this->contacts
     */
    public function isEmpty(): bool
    {
        return empty($this->contacts);
    }
}
