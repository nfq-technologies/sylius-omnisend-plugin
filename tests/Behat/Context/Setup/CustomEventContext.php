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
