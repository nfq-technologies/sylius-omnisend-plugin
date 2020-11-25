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

namespace Tests\NFQ\SyliusOmnisendPlugin\Behat\Context;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Tests\NFQ\SyliusOmnisendPlugin\Behat\Mock\LoggableClientMock;
use Webmozart\Assert\Assert;

class ClientRequestContext implements Context
{
    /** @var LoggableClientMock */
    private $loggableClientMock;

    public function __construct(LoggableClientMock $loggableClientMock)
    {
        $this->loggableClientMock = $loggableClientMock;
    }

    /** @Then Request to Omnisend was sent */
    public function requestToOmnisendWasSent()
    {
        Assert::true(null !== $this->loggableClientMock->getLastRequestData());
    }

    /**
     * @Then Request type :type to Omnisend endpoint :endpoint was sent
     * @Then Request type :type to Omnisend endpoint :endpoint should be sent
     */
    public function requestToOmnisendEndpointWasSent(string $type, string $endpoint)
    {
        $rawData = $this->loggableClientMock->getLastRequestData();

        Assert::same($type, $rawData['method']);
        Assert::contains($rawData['url'], $endpoint);
    }

    /**
     * @Then Request to Omnisend was sent with data:
     * @Then Omnisend request should contain data:
     */
    public function requestToOmnisendWasSentWithData(TableNode $table)
    {
        $rawData = $this->loggableClientMock->getRawLastRequestData();

        foreach ($table as $rowKey => $row) {
            $value = is_numeric($row['value']) ? $row['value'] : '"' . $row['value'] . '"';
            Assert::contains($rawData, '"' . $row['key'] .'":' . $value);
        }
    }

    /**
     * @Then order should have date of cancellation set
     */
    public function cancelledOrderDateShouldBeSet()
    {
        $rawData = $this->loggableClientMock->getLastRequestData();
        Assert::notNull($rawData['body']['canceledDate']);
    }
}
