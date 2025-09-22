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

namespace Tests\NFQ\SyliusOmnisendPlugin\Builder\Request;

use NFQ\SyliusOmnisendPlugin\Builder\Request\ContactBuilder;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\ContactIdentifier;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\ContactIdentifierChannelValue;
use NFQ\SyliusOmnisendPlugin\Factory\Request\ContactIdentifierFactory;
use NFQ\SyliusOmnisendPlugin\Resolver\DefaultCustomerAdditionalDataResolver;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\Address;
use Tests\NFQ\SyliusOmnisendPlugin\Application\Entity\Customer;
use DateTime;

class ContactBuilderTest extends TestCase
{
    private ContactIdentifierFactory&MockObject $factory;

    private ContactBuilder $builder;

    protected function setUp(): void
    {
        $this->factory = $this->createMock(ContactIdentifierFactory::class);
        $this->builder = new ContactBuilder($this->factory, new DefaultCustomerAdditionalDataResolver());
    }

    public function testIfAddsCustomProperties(): void
    {
        $customer = new Customer();
        $this->builder->createContact();
        $this->builder->addCustomProperties($customer);
        $result = $this->builder->getContact();

        $this->assertEquals($result->getTags(), null);
        $this->assertEquals($result->getCustomProperties(), null);
    }

    public function testIfNotAddsAddress(): void
    {
        $customer = new Customer();
        $this->builder->createContact();
        $this->builder->addAddress($customer);
        $result = $this->builder->getContact();

        $this->assertEquals($result->getCountryCode(), null);
    }

    public function testIfAddsAddress(): void
    {
        $customer = new Customer();
        $address = new Address();
        $address->setCountryCode('UK');
        $address->setProvinceName('Province');
        $address->setStreet('street');
        $address->setPostcode('LT-12222');
        $address->setCity('Vilnius');
        $customer->setDefaultAddress($address);
        $this->builder->createContact();
        $this->builder->addAddress($customer);
        $result = $this->builder->getContact();

        $this->assertEquals($result->getAddress(), 'street');
        $this->assertEquals($result->getCountryCode(), 'UK');
        $this->assertEquals($result->getCity(), 'Vilnius');
        $this->assertEquals($result->getPostalCode(), 'LT-12222');
        $this->assertEquals($result->getState(), 'Province');
    }

    public function testIfAddsCustomerDetails(): void
    {
        $customer = new Customer();
        $customer->setFirstName('first');
        $customer->setLastName('last');
        $customer->setGender('m');
        $customer->setBirthday(new DateTime('2019-01-02 12:12:12'));
        $this->builder->createContact();
        $this->builder->addCustomerDetails($customer);
        $result = $this->builder->getContact();

        $this->assertEquals($result->getFirstName(), 'first');
        $this->assertEquals($result->getLastName(), 'last');
        $this->assertEquals($result->getGender(), 'm');
        $this->assertEquals($result->getBirthday(), '2019-01-02T12:12:12+00:00');
    }

    /** @return bool[][]|null[][]|string[][] */
    public static function emailConsentProvider(): array
    {
        return [
            [null, ContactIdentifierChannelValue::NON_SUBSCRIBED],
            [true, ContactIdentifierChannelValue::SUBSCRIBED],
            [false, ContactIdentifierChannelValue::UNSUBSCRIBED],
        ];
    }

    #[DataProvider('emailConsentProvider')]
    public function testIfAddsEmailIdentifiers(?bool $emailConsent, string $expectedSubscriptionStatus): void
    {
        $this->factory
            ->expects($this->once())
            ->method('create')
            ->willReturnCallback(
                function (string $type, string $id, string $status) use ($expectedSubscriptionStatus) {
                    $this->assertEquals('email', $type);
                    $this->assertEquals('test@nfq.lt', $id);
                    $this->assertSame($expectedSubscriptionStatus, $status);

                    return new ContactIdentifier();
                }
            );
        $customer = new Customer();
        $customer->setEmail('test@nfq.lt');
        $customer->setSubscribedToEmail($emailConsent);
        $this->builder->createContact();
        $this->builder->addIdentifiers($customer);
    }

    public function testIfAddsPhoneIdentifiers(): void
    {
        $this->factory
            ->expects($this->once())
            ->method('create')
            ->willReturnCallback(
                function (string $type, string $id, string $status) {
                    $this->assertEquals('phone', $type);
                    $this->assertEquals('123423452', $id);
                    $this->assertEquals(ContactIdentifierChannelValue::NON_SUBSCRIBED, $status);

                    return new ContactIdentifier();
                }
            );
        $customer = new Customer();
        $customer->setPhoneNumber('123423452');
        $this->builder->createContact();
        $this->builder->addIdentifiers($customer);
    }

    public function testIfAddsBothIdentifiers(): void
    {
        $this->factory
            ->expects($this->exactly(2))
            ->method('create')
            ->willReturn(new ContactIdentifier());
        $customer = new Customer();
        $customer->setEmail('test@nfq.lt');
        $customer->setPhoneNumber('123423452');
        $this->builder->createContact();
        $this->builder->addIdentifiers($customer);
    }
}
