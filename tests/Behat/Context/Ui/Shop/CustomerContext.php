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

namespace Tests\NFQ\SyliusOmnisendPlugin\Behat\Context\Ui\Shop;

use Behat\Behat\Context\Context;
use NFQ\SyliusOmnisendPlugin\Model\ContactAwareInterface;
use Sylius\Component\Core\Repository\CustomerRepositoryInterface;
use Webmozart\Assert\Assert;

class CustomerContext implements Context
{
    /** @var CustomerRepositoryInterface */
    private $customerRepository;

    public function __construct(CustomerRepositoryInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /** @Then Customer with :email should have omnisend contact id */
    public function customerHasOmnisendClientId(string $email): void
    {
        /** @var ContactAwareInterface $customer */
        $customer = $this->customerRepository->findOneBy(['email' => $email]);

        Assert::isInstanceOf($customer, ContactAwareInterface::class);
        Assert::notNull($customer->getOmnisendContactId());
    }
}
