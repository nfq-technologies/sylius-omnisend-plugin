<?php

/*
 * This file is part of the NFQ package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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

    public function set(?string $contactId): void
    {
        if (null !== $contactId) {
            $this->cookieStorage->set(self::COOKIE_NAME, $contactId);
        }
    }
}
