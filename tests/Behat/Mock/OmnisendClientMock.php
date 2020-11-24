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

namespace Tests\NFQ\SyliusOmnisendPlugin\Behat\Mock;

use NFQ\SyliusOmnisendPlugin\Client\OmnisendClientInterface;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\Contact;
use NFQ\SyliusOmnisendPlugin\Client\Response\Model\ContactSuccess;

class OmnisendClientMock implements OmnisendClientInterface
{
    public function postContact(Contact $contact, ?string $channelCode): ?object
    {
        return (new ContactSuccess())
            ->setContactID('testId');
    }

    public function patchContact(string $contactId, Contact $contact, ?string $channelCode): void
    {
    }
}
