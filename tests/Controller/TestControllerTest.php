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

namespace Tests\NFQ\SyliusOmnisendPlugin\Controller;

use NFQ\SyliusOmnisendPlugin\Controller\TestController;
use PHPUnit\Framework\TestCase;

class TestControllerTest extends TestCase
{
    public function testActionResponse()
    {
        $testController = new TestController();

        $this->assertEquals('Omnisend', $testController->__invoke()->getContent());
    }
}
