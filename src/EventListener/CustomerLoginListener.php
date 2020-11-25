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

namespace NFQ\SyliusOmnisendPlugin\EventListener;

use NFQ\SyliusOmnisendPlugin\Model\ContactAwareInterface;
use NFQ\SyliusOmnisendPlugin\Setter\ContactCookieSetter;
use Sylius\Component\Core\Model\ShopUserInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class CustomerLoginListener
{
    /** @var ContactCookieSetter */
    private $contactCookieSetter;

    public function __construct(ContactCookieSetter $omnisendClient)
    {
        $this->contactCookieSetter = $omnisendClient;
    }

    /**
     * @param InteractiveLoginEvent $interactiveLoginEvent
     */
    public function onInteractiveLogin(InteractiveLoginEvent $interactiveLoginEvent): void
    {
        $user = $interactiveLoginEvent->getAuthenticationToken()->getUser();

        if (false === ($user instanceof ShopUserInterface)) {
            return;
        }
        /** @var ContactAwareInterface $customer */
        $customer = $user->getCustomer();

        if (null !== $customer->getOmnisendContactId()) {
            $this->contactCookieSetter->set($customer->getOmnisendContactId());
        }
    }
}
