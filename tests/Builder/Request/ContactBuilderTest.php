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

namespace Tests\NFQ\SyliusOmnisendPlugin\Builder\Request;

use NFQ\SyliusOmnisendPlugin\Builder\Request\ContactBuilder;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\ContactIdentifier;
use NFQ\SyliusOmnisendPlugin\Factory\Request\ContactIdentifierFactory;
use NFQ\SyliusOmnisendPlugin\Resolver\DefaultCustomerAdditionalDataResolver;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\Address;
use Tests\NFQ\SyliusOmnisendPlugin\Application\Entity\Customer;
use DateTime;

class ContactBuilderTest extends TestCase
{
    /** @var ContactIdentifierFactory */
    private $factory;

    /** @var ContactBuilder */
    private $builder;

    protected function setUp(): void
    {
        $this->factory = $this->createMock(ContactIdentifierFactory::class);
        $this->builder = new ContactBuilder($this->factory, new DefaultCustomerAdditionalDataResolver());
    }

    public function testIfAddsCustomProperties()
    {
        $customer = new Customer();
        $this->builder->createContact();
        $this->builder->addCustomProperties($customer);
        $result = $this->builder->getContact();

        $this->assertEquals($result->getTags(), null);
        $this->assertEquals($result->getCustomProperties(), null);
    }

    public function testIfNotAddsAddress()
    {
        $customer = new Customer();
        $this->builder->createContact();
        $this->builder->addAddress($customer);
        $result = $this->builder->getContact();

        $this->assertEquals($result->getCountryCode(), null);
    }

    public function testIfAddsAddress()
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

    public function testIfAddsCustomerDetails()
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

    public function testIfAddsEmailIdentifiers()
    {
        $this->factory
            ->expects($this->once())
            ->method('create')
            ->willReturnCallback(
                function (string $type, string $id, string $status) {
                    $this->assertEquals('email', $type);
                    $this->assertEquals('test@nfq.lt', $id);
                    $this->assertEquals('subscribed', $status);

                    return new ContactIdentifier();
                }
            );
        $customer = new Customer();
        $customer->setEmail('test@nfq.lt');
        $customer->setSubscribedToNewsletter(true);
        $this->builder->createContact();
        $this->builder->addIdentifiers($customer);
    }

    public function testIfAddsPhoneIdentifiers()
    {
        $this->factory
            ->expects($this->once())
            ->method('create')
            ->willReturnCallback(
                function (string $type, string $id, string $status) {
                    $this->assertEquals('phone', $type);
                    $this->assertEquals('123423452', $id);
                    $this->assertEquals('nonSubscribed', $status);

                    return new ContactIdentifier();
                }
            );
        $customer = new Customer();
        $customer->setPhoneNumber('123423452');
        $this->builder->createContact();
        $this->builder->addIdentifiers($customer);
    }

    public function testIfAddsBothIdentifiers()
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
