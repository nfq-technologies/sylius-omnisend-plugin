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

namespace Tests\NFQ\SyliusOmnisendPlugin\Builder\Request;

use NFQ\SyliusOmnisendPlugin\Builder\Request\ContactBuilderDirector;
use NFQ\SyliusOmnisendPlugin\Builder\Request\ContactBuilderInterface;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\Contact;
use PHPUnit\Framework\TestCase;
use Tests\NFQ\SyliusOmnisendPlugin\Application\Entity\Customer;

class ContactBuilderDirectorTest extends TestCase
{
    /** @var ContactBuilderDirector */
    private $director;

    /** @var ContactBuilderInterface */
    private $builder;

    protected function setUp(): void
    {
        $this->builder = $this->createMock(ContactBuilderInterface::class);
        $this->director = new ContactBuilderDirector($this->builder);
    }

    public function testIfBuilds()
    {
        $customer = new Customer();
        $this->builder->expects($this->once())->method('createContact');
        $this->builder->expects($this->once())->method('addIdentifiers');
        $this->builder->expects($this->once())->method('addCustomerDetails');
        $this->builder->expects($this->once())->method('addAddress');
        $this->builder->expects($this->once())->method('addCustomProperties');
        $this->builder
            ->expects($this->once())
            ->method('getContact')
            ->willReturn(new Contact());

        $result = $this->director->build($customer);

        $this->assertInstanceOf(Contact::class, $result);
    }
}
