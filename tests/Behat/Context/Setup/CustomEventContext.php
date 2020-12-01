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

namespace Tests\NFQ\SyliusOmnisendPlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Tests\NFQ\SyliusOmnisendPlugin\Behat\Provider\CustomEventProvider;

class CustomEventContext implements Context
{
    /** @var CustomEventProvider */
    private $eventProvider;

    public function __construct(CustomEventProvider $eventProvider)
    {
        $this->eventProvider = $eventProvider;
    }

    /** @Then the store has custom event :name with fields: */
    public function iSeeOmnisendProductPickerScript(string $name, TableNode $table)
    {
        $fields = [];
        foreach ($table as $rowKey => $row) {
            $fields[$row['key']] = $row['value'];
        }

        $this->eventProvider->createEventWithFields($name, $fields);
    }
}
