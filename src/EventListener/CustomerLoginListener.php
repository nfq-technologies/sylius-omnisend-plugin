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
