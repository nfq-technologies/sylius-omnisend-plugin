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

namespace NFQ\SyliusOmnisendPlugin\Setter;

use Sylius\Bundle\ResourceBundle\Storage\CookieStorage;

class ContactCookieSetter
{
    public const COOKIE_NAME = 'omnisendContactID';

    /** @var CookieStorage */
    private $cookieStorage;

    public function __construct(CookieStorage $cookieStorage)
    {
        $this->cookieStorage = $cookieStorage;
    }

    public function set(string $contactId)
    {
        $this->cookieStorage->set(self::COOKIE_NAME, $contactId);
    }
}
