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
