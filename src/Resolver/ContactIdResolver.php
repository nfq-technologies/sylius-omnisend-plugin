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

namespace NFQ\SyliusOmnisendPlugin\Resolver;

use NFQ\SyliusOmnisendPlugin\Model\ContactAwareInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class ContactIdResolver implements ContactIdResolverInterface
{
    /** @var Request|null */
    private $request;

    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
    }

    public function resolve(OrderInterface $order): ?string
    {
        /** @var ContactAwareInterface|null $customer */
        $customer = $order->getCustomer();

        if (null !== $customer && null !== $customer->getOmnisendContactId()) {
            return $customer->getOmnisendContactId();
        } elseif (
            null !== $this->request &&
            null !== $this->request->cookies &&
            $this->request->cookies->has('omnisendContactID')
        ) {
            return $this->request->cookies->get('omnisendContactID');
        }

        return null;
    }
}
