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

namespace Tests\NFQ\SyliusOmnisendPlugin\Manager;

use InvalidArgumentException;
use NFQ\SyliusOmnisendPlugin\Builder\Request\ContactBuilderDirector;
use NFQ\SyliusOmnisendPlugin\Builder\Request\ContactBuilderDirectorInterface;
use NFQ\SyliusOmnisendPlugin\Client\OmnisendClientInterface;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\Contact;
use NFQ\SyliusOmnisendPlugin\Client\Response\Model\ContactSuccess;
use NFQ\SyliusOmnisendPlugin\Client\Response\Model\ContactSuccessList;
use NFQ\SyliusOmnisendPlugin\Manager\ContactManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Repository\CustomerRepositoryInterface;
use Tests\NFQ\SyliusOmnisendPlugin\Application\Entity\Customer;

class ContactManagerTest extends TestCase
{
    private const PHONE_NUMBER = '+37061111111';

    private ContactManager $manager;
    private ContactBuilderDirectorInterface&MockObject $contactBuilderDirector;
    private OmnisendClientInterface&MockObject $omnisendClient;
    private CustomerRepositoryInterface&MockObject $customerRepository;

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

    public function testIfAddOmnisendContactID(): void
    {
        $customer = new Customer();
        $customer->setEmail('emai@sylius.com');
        $customer->setPhoneNumber(self::PHONE_NUMBER);
        $this->contactBuilderDirector
            ->expects($this->once())
            ->method('build')
            ->willReturn(new Contact());
        $this->omnisendClient
            ->expects($this->once())
            ->method('postContact')
            ->willReturn((new ContactSuccess())->setContactID('ID'));
        $this->omnisendClient
            ->expects($this->once())
            ->method('getContactByEmail')
            ->willReturn(null);
        $this->omnisendClient
            ->expects($this->once())
            ->method('getContactByPhone')
            ->willReturn(null);
        $this->customerRepository
            ->expects($this->once())
            ->method('add');

        $this->manager->pushToOmnisend($customer, 'default');

        $this->assertEquals($customer->getOmnisendContactId(), 'ID');
    }

    public function testIfThrowsExceptionWhenPostContactReturnsNull(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Failed to create contact');

        $customer = new Customer();
        $customer->setEmail('emai@sylius.com');
        $customer->setPhoneNumber(self::PHONE_NUMBER);
        $this->contactBuilderDirector
            ->expects($this->once())
            ->method('build')
            ->willReturn(new Contact());
        $this->omnisendClient
            ->expects($this->once())
            ->method('postContact')
            ->willReturn(null);
        $this->customerRepository
            ->expects($this->never())
            ->method('add');
        $this->omnisendClient
            ->expects($this->once())
            ->method('getContactByEmail')
            ->willReturn(null);
        $this->omnisendClient
            ->expects($this->once())
            ->method('getContactByPhone')
            ->willReturn(null);

        $this->manager->pushToOmnisend($customer, 'default');
    }

    public function testUpdateContactWhenOmnisendContactIdExists(): void
    {
        $customer = new Customer();
        $customer->setEmail('email@sylius.com');
        $customer->setOmnisendContactId('EXISTING_ID');

        $this->contactBuilderDirector
            ->expects($this->once())
            ->method('build')
            ->willReturn(new Contact());
        $this->omnisendClient
            ->expects($this->once())
            ->method('patchContact')
            ->with('EXISTING_ID', $this->anything(), 'default')
            ->willReturn((new ContactSuccess())->setContactID('EXISTING_ID'));
        $this->omnisendClient
            ->expects($this->never())
            ->method('getContactByEmail');
        $this->omnisendClient
            ->expects($this->never())
            ->method('postContact');

        $result = $this->manager->pushToOmnisend($customer, 'default');

        $this->assertEquals('EXISTING_ID', $result->getContactID());
    }

    public function testUpdateByEmailWhenContactFoundByEmail(): void
    {
        $customer = new Customer();
        $customer->setEmail('email@sylius.com');
        $customer->setPhoneNumber(self::PHONE_NUMBER);

        $existingContact = (new ContactSuccess())->setContactID('EMAIL_CONTACT_ID');
        $contactList = new ContactSuccessList();
        $contactList->setContacts([$existingContact]);

        $this->contactBuilderDirector
            ->expects($this->exactly(2))
            ->method('build')
            ->willReturn(new Contact());
        $this->omnisendClient
            ->expects($this->once())
            ->method('getContactByEmail')
            ->with('email@sylius.com', 'default')
            ->willReturn($contactList);
        $this->omnisendClient
            ->expects($this->once())
            ->method('getContactByPhone')
            ->willReturn(null);
        $this->omnisendClient
            ->expects($this->once())
            ->method('patchContact')
            ->with('EMAIL_CONTACT_ID', $this->anything(), 'default');
        $this->omnisendClient
            ->expects($this->never())
            ->method('postContact');
        $this->customerRepository
            ->expects($this->once())
            ->method('add');

        $result = $this->manager->pushToOmnisend($customer, 'default');

        $this->assertEquals('EMAIL_CONTACT_ID', $customer->getOmnisendContactId());
    }

    public function testUpdateByPhoneWhenContactFoundByPhone(): void
    {
        $customer = new Customer();
        $customer->setPhoneNumber(self::PHONE_NUMBER);

        $existingContact = (new ContactSuccess())->setContactID('PHONE_CONTACT_ID');
        $contactList = new ContactSuccessList();
        $contactList->setContacts([$existingContact]);

        $this->contactBuilderDirector
            ->expects($this->exactly(2))
            ->method('build')
            ->willReturn(new Contact());
        $this->omnisendClient
            ->expects($this->once())
            ->method('getContactByPhone')
            ->willReturn($contactList);
        $this->omnisendClient
            ->expects($this->once())
            ->method('patchContact')
            ->with('PHONE_CONTACT_ID', $this->anything(), 'default');
        $this->omnisendClient
            ->expects($this->never())
            ->method('postContact');
        $this->customerRepository
            ->expects($this->once())
            ->method('add');

        $result = $this->manager->pushToOmnisend($customer, 'default');

        $this->assertEquals('PHONE_CONTACT_ID', $customer->getOmnisendContactId());
    }

    public function testPhoneContactTakesPriorityWhenBothFound(): void
    {
        $customer = new Customer();
        $customer->setEmail('email@sylius.com');
        $customer->setPhoneNumber(self::PHONE_NUMBER);

        $emailContact = (new ContactSuccess())->setContactID('EMAIL_CONTACT_ID');
        $emailContactList = new ContactSuccessList();
        $emailContactList->setContacts([$emailContact]);

        $phoneContact = (new ContactSuccess())->setContactID('PHONE_CONTACT_ID');
        $phoneContactList = new ContactSuccessList();
        $phoneContactList->setContacts([$phoneContact]);

        $this->contactBuilderDirector
            ->method('build')
            ->willReturn(new Contact());
        $this->omnisendClient
            ->expects($this->once())
            ->method('getContactByEmail')
            ->willReturn($emailContactList);
        $this->omnisendClient
            ->expects($this->once())
            ->method('getContactByPhone')
            ->willReturn($phoneContactList);
        $this->omnisendClient
            ->expects($this->exactly(2))
            ->method('patchContact');

        $result = $this->manager->pushToOmnisend($customer, 'default');

        $this->assertEquals('PHONE_CONTACT_ID', $result->getContactID());
    }

    public function testSkipEmailSearchWhenEmailEmpty(): void
    {
        $customer = new Customer();
        $customer->setPhoneNumber(self::PHONE_NUMBER);

        $this->contactBuilderDirector
            ->expects($this->once())
            ->method('build')
            ->willReturn(new Contact());
        $this->omnisendClient
            ->expects($this->never())
            ->method('getContactByEmail');
        $this->omnisendClient
            ->expects($this->once())
            ->method('getContactByPhone')
            ->willReturn(null);
        $this->omnisendClient
            ->expects($this->once())
            ->method('postContact')
            ->willReturn((new ContactSuccess())->setContactID('NEW_ID'));

        $this->manager->pushToOmnisend($customer, 'default');

        $this->assertEquals('NEW_ID', $customer->getOmnisendContactId());
    }

    public function testSkipPhoneSearchWhenPhoneEmpty(): void
    {
        $customer = new Customer();
        $customer->setEmail('email@sylius.com');

        $this->contactBuilderDirector
            ->expects($this->once())
            ->method('build')
            ->willReturn(new Contact());
        $this->omnisendClient
            ->expects($this->once())
            ->method('getContactByEmail')
            ->willReturn(null);
        $this->omnisendClient
            ->expects($this->never())
            ->method('getContactByPhone');
        $this->omnisendClient
            ->expects($this->once())
            ->method('postContact')
            ->willReturn((new ContactSuccess())->setContactID('NEW_ID'));

        $this->manager->pushToOmnisend($customer, 'default');

        $this->assertEquals('NEW_ID', $customer->getOmnisendContactId());
    }
}
