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
