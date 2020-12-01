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
