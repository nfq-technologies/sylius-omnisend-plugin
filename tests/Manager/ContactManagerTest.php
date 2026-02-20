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
use Webmozart\Assert\InvalidArgumentException;

class ContactManagerTest extends TestCase
{
    private const EMAIL = 'email@sylius.com';
    private const PHONE_NUMBER = '+37061111111';
    private const CONTACT_ID_1 = '5f8e9a1b';
    private const CONTACT_ID_2 = '7c3d4e2f';
    private const NEW_CONTACT_ID = 'NEW_ID';
    private const NEW_CONTACT_ID_1 = '1a2b3c4d';
    private const NEW_CONTACT_ID_2 = '2d4f6a8c';

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
            $this->customerRepository,
        );
    }

    public function testCreateContactWithBothWhenNotFoundByEmailOrPhone(): void
    {
        $customer = new Customer();
        $customer->setEmail(self::EMAIL);
        $customer->setPhoneNumber(self::PHONE_NUMBER);

        $this->contactBuilderDirector
            ->expects($this->once())
            ->method('build')
            ->willReturn(new Contact());
        $this->omnisendClient
            ->expects($this->once())
            ->method('getContactByEmail')
            ->willReturn(null);
        $this->omnisendClient
            ->expects($this->once())
            ->method('getContactByPhone')
            ->willReturn(null);
        $this->omnisendClient
            ->expects($this->once())
            ->method('postContact')
            ->willReturn((new ContactSuccess())->setContactID(self::NEW_CONTACT_ID));
        $this->customerRepository
            ->expects($this->once())
            ->method('add');

        $result = $this->manager->pushToOmnisend($customer, 'default');

        $this->assertEquals(self::NEW_CONTACT_ID, $customer->getOmnisendContactId());
        $this->assertEquals(self::NEW_CONTACT_ID, $result->getContactID());
    }

    public function testThrowsExceptionWhenPostContactReturnsNull(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Failed to create contact');

        $customer = new Customer();
        $customer->setEmail(self::EMAIL);
        $customer->setPhoneNumber(self::PHONE_NUMBER);

        $this->contactBuilderDirector
            ->method('build')
            ->willReturn(new Contact());
        $this->contactBuilderDirector
            ->method('buildWithoutPhone')
            ->willReturn(new Contact());
        $this->contactBuilderDirector
            ->method('buildWithoutEmail')
            ->willReturn(new Contact());
        $this->omnisendClient
            ->expects($this->once())
            ->method('getContactByEmail')
            ->willReturn(null);
        $this->omnisendClient
            ->expects($this->once())
            ->method('getContactByPhone')
            ->willReturn(null);
        $this->omnisendClient
            ->expects($this->once())
            ->method('postContact')
            ->willReturn(null);
        $this->customerRepository
            ->expects($this->never())
            ->method('add');

        $this->manager->pushToOmnisend($customer, 'default');
    }

    public function testUpdateConsentOnlyWhenContactFoundByEmail(): void
    {
        $customer = new Customer();
        $customer->setEmail(self::EMAIL);
        $customer->setPhoneNumber(self::PHONE_NUMBER);

        $emailContact = (new ContactSuccess())->setContactID(self::CONTACT_ID_1);
        $emailContactList = new ContactSuccessList();
        $emailContactList->setContacts([$emailContact]);

        $this->contactBuilderDirector
            ->expects($this->once())
            ->method('buildWithoutEmail')
            ->willReturn(new Contact());
        $this->omnisendClient
            ->expects($this->once())
            ->method('getContactByEmail')
            ->willReturn($emailContactList);
        $this->omnisendClient
            ->expects($this->once())
            ->method('getContactByPhone')
            ->willReturn(null);
        // Patch for email consent, then postContact for new phone contact
        $this->omnisendClient
            ->expects($this->once())
            ->method('patchContact')
            ->with(self::CONTACT_ID_1, $this->isInstanceOf(Contact::class), 'default');
        $this->omnisendClient
            ->expects($this->once())
            ->method('postContact')
            ->willReturn((new ContactSuccess())->setContactID(self::NEW_CONTACT_ID_1));

        $result = $this->manager->pushToOmnisend($customer, 'default');

        // Phone contact is returned (newly created) when email found but phone not
        $this->assertEquals(self::NEW_CONTACT_ID_1, $result->getContactID());
    }

    public function testUpdateConsentOnlyWhenContactFoundByPhone(): void
    {
        $customer = new Customer();
        $customer->setEmail(self::EMAIL);
        $customer->setPhoneNumber(self::PHONE_NUMBER);

        $phoneContact = (new ContactSuccess())->setContactID(self::CONTACT_ID_2);
        $phoneContactList = new ContactSuccessList();
        $phoneContactList->setContacts([$phoneContact]);

        $this->contactBuilderDirector
            ->expects($this->once())
            ->method('buildWithoutPhone')
            ->willReturn(new Contact());

        $this->omnisendClient
            ->expects($this->once())
            ->method('getContactByEmail')
            ->willReturn(null);
        $this->omnisendClient
            ->expects($this->once())
            ->method('getContactByPhone')
            ->willReturn($phoneContactList);
        // Patch for phone consent, then postContact for new email contact
        $this->omnisendClient
            ->expects($this->once())
            ->method('patchContact')
            ->with(self::CONTACT_ID_2, $this->isInstanceOf(Contact::class), 'default');
        $this->omnisendClient
            ->expects($this->once())
            ->method('postContact')
            ->willReturn((new ContactSuccess())->setContactID(self::NEW_CONTACT_ID_2));

        $result = $this->manager->pushToOmnisend($customer, 'default');

        $this->assertEquals(self::CONTACT_ID_2, $result->getContactID());
    }

    public function testUpdateConsentOnlyWhenBothContactsFound(): void
    {
        $customer = new Customer();
        $customer->setEmail(self::EMAIL);
        $customer->setPhoneNumber(self::PHONE_NUMBER);

        $emailContact = (new ContactSuccess())->setContactID(self::CONTACT_ID_1);
        $emailContactList = new ContactSuccessList();
        $emailContactList->setContacts([$emailContact]);

        $phoneContact = (new ContactSuccess())->setContactID(self::CONTACT_ID_2);
        $phoneContactList = new ContactSuccessList();
        $phoneContactList->setContacts([$phoneContact]);

        $this->omnisendClient
            ->expects($this->once())
            ->method('getContactByEmail')
            ->willReturn($emailContactList);
        $this->omnisendClient
            ->expects($this->once())
            ->method('getContactByPhone')
            ->willReturn($phoneContactList);
        // Two patches: one for email consent, one for phone consent
        $this->omnisendClient
            ->expects($this->exactly(2))
            ->method('patchContact');
        $this->omnisendClient
            ->expects($this->never())
            ->method('postContact');

        $result = $this->manager->pushToOmnisend($customer, 'default');

        // Phone contact takes priority when both found
        $this->assertEquals(self::CONTACT_ID_2, $result->getContactID());
    }

    public function testSkipEmailSearchWhenEmailEmpty(): void
    {
        $customer = new Customer();
        $customer->setPhoneNumber(self::PHONE_NUMBER);

        $this->contactBuilderDirector
            ->method('build')
            ->willReturn(new Contact());
        $this->contactBuilderDirector
            ->method('buildWithoutEmail')
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
            ->willReturn((new ContactSuccess())->setContactID(self::NEW_CONTACT_ID));

        $result = $this->manager->pushToOmnisend($customer, 'default');

        $this->assertEquals(self::NEW_CONTACT_ID, $result->getContactID());
    }

    public function testSkipPhoneSearchWhenPhoneEmpty(): void
    {
        $customer = new Customer();
        $customer->setEmail(self::EMAIL);

        $this->contactBuilderDirector
            ->method('build')
            ->willReturn(new Contact());
        $this->contactBuilderDirector
            ->method('buildWithoutPhone')
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
            ->willReturn((new ContactSuccess())->setContactID(self::NEW_CONTACT_ID));

        $result = $this->manager->pushToOmnisend($customer, 'default');

        $this->assertEquals(self::NEW_CONTACT_ID, $result->getContactID());
    }

    public function testEmailFoundNoPhoneOnCustomer(): void
    {
        $customer = new Customer();
        $customer->setEmail(self::EMAIL);

        $emailContact = (new ContactSuccess())->setContactID(self::CONTACT_ID_1);
        $emailContactList = new ContactSuccessList();
        $emailContactList->setContacts([$emailContact]);

        $this->omnisendClient
            ->expects($this->once())
            ->method('getContactByEmail')
            ->willReturn($emailContactList);
        $this->omnisendClient
            ->expects($this->never())
            ->method('getContactByPhone');
        $this->omnisendClient
            ->expects($this->once())
            ->method('patchContact')
            ->with(self::CONTACT_ID_1, $this->isInstanceOf(Contact::class), 'default');
        $this->omnisendClient
            ->expects($this->never())
            ->method('postContact');

        $result = $this->manager->pushToOmnisend($customer, 'default');

        $this->assertEquals(self::CONTACT_ID_1, $result->getContactID());
    }

    public function testPhoneFoundNoEmailOnCustomer(): void
    {
        $customer = new Customer();
        $customer->setPhoneNumber(self::PHONE_NUMBER);

        $phoneContact = (new ContactSuccess())->setContactID(self::CONTACT_ID_2);
        $phoneContactList = new ContactSuccessList();
        $phoneContactList->setContacts([$phoneContact]);

        $this->omnisendClient
            ->expects($this->never())
            ->method('getContactByEmail');
        $this->omnisendClient
            ->expects($this->once())
            ->method('getContactByPhone')
            ->willReturn($phoneContactList);
        $this->omnisendClient
            ->expects($this->once())
            ->method('patchContact')
            ->with(self::CONTACT_ID_2, $this->isInstanceOf(Contact::class), 'default');
        $this->omnisendClient
            ->expects($this->never())
            ->method('postContact');

        $result = $this->manager->pushToOmnisend($customer, 'default');

        $this->assertEquals(self::CONTACT_ID_2, $result->getContactID());
    }

    public function testThrowsExceptionWhenBothEmailAndPhoneEmpty(): void
    {
        $customer = new Customer();

        $this->contactBuilderDirector
            ->expects($this->never())
            ->method('build')
            ->willReturn(new Contact());
        $this->omnisendClient
            ->expects($this->never())
            ->method('getContactByEmail');
        $this->omnisendClient
            ->expects($this->never())
            ->method('getContactByPhone');
        $this->omnisendClient
            ->expects($this->never())
            ->method('postContact')
            ->willReturn((new ContactSuccess())->setContactID(self::NEW_CONTACT_ID));
        $this->customerRepository
            ->expects($this->never())
            ->method('add');

        $this->expectException(InvalidArgumentException::class);
        $result = $this->manager->pushToOmnisend($customer, 'default');
    }
}
