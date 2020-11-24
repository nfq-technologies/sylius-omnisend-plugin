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

namespace Tests\NFQ\SyliusOmnisendPlugin\Behat\Context\Hook;

use Behat\Behat\Context\Context;
use Tests\NFQ\SyliusOmnisendPlugin\Behat\Mock\LoggableClientMock;

class ClientRequestHook implements Context
{
    /** @var LoggableClientMock */
    private $clientMock;

    public function __construct(LoggableClientMock $clientMock)
    {
        $this->clientMock = $clientMock;
    }

    /**
     * @BeforeScenario
     */
    public function clearLastRequest()
    {
        if (file_exists($this->clientMock->getFile())) {
            unlink($this->clientMock->getFile());
        }
    }
}
