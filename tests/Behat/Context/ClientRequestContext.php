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

    /** @Then Request type :type to Omnisend endpoint :endpoint was sent */
    public function requestToOmnisendEndpointWasSent(string $type, string $endpoint)
    {
        $rawData = $this->loggableClientMock->getLastRequestData();

        Assert::same($type, $rawData['method']);
        Assert::contains($rawData['url'], $endpoint);
    }

    /**
     * @Then Request to Omnisend was sent with data:
     * @Then Omnisend request should contains data:
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
     * @Then cancelled order date should be set
     */
    public function cancelledOrderDateshouldBeSet()
    {
        $rawData = $this->loggableClientMock->getLastRequestData();
        Assert::notNull($rawData['body']['canceledDate']);
    }
}
