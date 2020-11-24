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

namespace Tests\NFQ\SyliusOmnisendPlugin\Factory\Request;

use NFQ\SyliusOmnisendPlugin\Factory\Request\BatchFactory;
use PHPUnit\Framework\TestCase;

class BatchFactoryTest extends TestCase
{
    /** @var BatchFactory */
    private $factory;

    protected function setUp(): void
    {
        $this->factory = new BatchFactory();
    }

    public function testIfCreatesWell()
    {
        $result = $this->factory->create(
            'POST',
            'categories',
            ['aaa'],
            '4444'
        );

        $this->assertEquals($result->getItems(), ['aaa']);
        $this->assertEquals($result->getMethod(), 'POST');
        $this->assertEquals($result->getEndpoint(), 'categories');
        $this->assertEquals($result->getEventID(), '4444');
    }
}
