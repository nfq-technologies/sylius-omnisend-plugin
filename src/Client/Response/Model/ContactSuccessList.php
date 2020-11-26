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

namespace NFQ\SyliusOmnisendPlugin\Client\Response\Model;

class ContactSuccessList
{
    /** @var ContactSuccess[] */
    private $contacts;

    public function __construct()
    {
        $this->contacts = [];
    }

    public function getContacts(): array
    {
        return $this->contacts;
    }

    public function addContact(ContactSuccess $contact): void
    {
        $this->contacts[] = $contact;
    }

    public function setContacts(array $contacts): void
    {
        $this->contacts = $contacts;
    }
}
