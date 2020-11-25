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

namespace Tests\NFQ\SyliusOmnisendPlugin\Manager;

use NFQ\SyliusOmnisendPlugin\Builder\Request\ContactBuilderDirector;
use NFQ\SyliusOmnisendPlugin\Builder\Request\ContactBuilderDirectorInterface;
use NFQ\SyliusOmnisendPlugin\Client\OmnisendClientInterface;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\Contact;
use NFQ\SyliusOmnisendPlugin\Client\Response\Model\ContactSuccess;
use NFQ\SyliusOmnisendPlugin\Manager\ContactManager;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Repository\CustomerRepositoryInterface;
use Tests\NFQ\SyliusOmnisendPlugin\Application\Entity\Customer;

class ContactManagerTest extends TestCase
{
    /** @var ContactManager */
    private $manager;

    /** @var ContactBuilderDirector */
    private $contactBuilderDirector;

    /** @var OmnisendClientInterface */
    private $omnisendClient;

    /** @var CustomerRepositoryInterface */
    private $customerRepository;

    protected function setUp(): void
    {
        $this->contactBuilderDirector = $this->createMock(ContactBuilderDirectorInterface::class);
        $this->omnisendClient = $this->createMock(OmnisendClientInterface::class);
        $this->customerRepository = $this->createMock(CustomerRepositoryInterface::class);

        $this->manager = new ContactManager(
            $this->contactBuilderDirector,
            $this->omnisendClient,
            $this->customerRepository
        );
    }

    public function testIfAddOmnisendContactID()
    {
        $customer = new Customer();
        $customer->setEmail('emai@sylius.com');
        $this->contactBuilderDirector
            ->expects($this->exactly(1))
            ->method('build')
            ->willReturn(new Contact());
        $this->omnisendClient
            ->expects($this->exactly(1))
            ->method('postContact')
            ->willReturn((new ContactSuccess())->setContactID('ID'));
        $this->omnisendClient
            ->expects($this->exactly(1))
            ->method('getContactByEmail')
            ->willReturn(null);
        $this->customerRepository
            ->expects($this->exactly(1))
            ->method('add');

        $this->manager->pushToOmnisend($customer, 'default');

        $this->assertEquals($customer->getOmnisendContactId(), 'ID');
    }

    public function testIfDoNotAddOmnisendContactIDOnInvalidResponse()
    {
        $customer = new Customer();
        $customer->setEmail('emai@sylius.com');
        $this->contactBuilderDirector
            ->expects($this->exactly(1))
            ->method('build')
            ->willReturn(new Contact());
        $this->omnisendClient
            ->expects($this->exactly(1))
            ->method('postContact')
            ->willReturn(null);
        $this->customerRepository
            ->expects($this->never())
            ->method('add');
        $this->omnisendClient
            ->expects($this->exactly(1))
            ->method('getContactByEmail')
            ->willReturn(null);

        $this->manager->pushToOmnisend($customer, 'default');

        $this->assertEquals($customer->getOmnisendContactId(), null);
    }
}
