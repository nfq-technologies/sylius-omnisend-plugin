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

namespace NFQ\SyliusOmnisendPlugin\Builder\Request;

use NFQ\SyliusOmnisendPlugin\Client\Request\Model\Contact;
use NFQ\SyliusOmnisendPlugin\Model\ContactAwareInterface;
use Sylius\Component\Core\Model\CustomerInterface;

class ContactBuilderDirector implements ContactBuilderDirectorInterface
{
    /** @var ContactBuilderInterface */
    private $contactBuilder;

    public function __construct(ContactBuilderInterface $contactBuilder)
    {
        $this->contactBuilder = $contactBuilder;
    }

    public function build(ContactAwareInterface $customer): Contact
    {
        $this->contactBuilder->createContact();
        $this->contactBuilder->addIdentifiers($customer);
        $this->contactBuilder->addCustomerDetails($customer);
        $this->contactBuilder->addAddress($customer);
        $this->contactBuilder->addCustomProperties($customer);

        return $this->contactBuilder->getContact();
    }
}
