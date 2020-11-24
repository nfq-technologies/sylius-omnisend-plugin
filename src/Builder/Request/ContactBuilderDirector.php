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

namespace NFQ\SyliusOmnisendPlugin\Builder\Request;

use NFQ\SyliusOmnisendPlugin\Client\Request\Model\Contact;
use Sylius\Component\Core\Model\CustomerInterface;

class ContactBuilderDirector implements ContactBuilderDirectorInterface
{
    /** @var ContactBuilderInterface */
    private $contactBuilder;

    public function __construct(ContactBuilderInterface $contactBuilder)
    {
        $this->contactBuilder = $contactBuilder;
    }

    public function build(CustomerInterface $customer): Contact
    {
        $this->contactBuilder->createContact();
        $this->contactBuilder->addIdentifiers($customer);
        $this->contactBuilder->addCustomerDetails($customer);
        $this->contactBuilder->addAddress($customer);
        $this->contactBuilder->addCustomProperties($customer);

        return $this->contactBuilder->getContact();
    }
}
