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

namespace Tests\NFQ\SyliusOmnisendPlugin\Behat\Context\Ui\Shop;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Sylius\Behat\Page\Shop\HomePage;
use Webmozart\Assert\Assert;

class OmnisendTrackingScriptContext implements Context
{
    /** @var HomePage */
    private $homePage;

    public function __construct(HomePage $homePage)
    {
        $this->homePage = $homePage;
    }

    /**
     * @Then I see omnisend tracking script with a key :key
     */
    public function iSeeOmnisendTrackingScriptWithKey(string $key)
    {
        Assert::contains($this->homePage->getContent(), 'omnisend.push(["accountID", "' . $key . '"])');
    }

    /**
     * @Then I do not see omnisend tracking script
     */
    public function iDoNotSeeOmnisendTrackingScript()
    {
        Assert::notContains($this->homePage->getContent(), 'window.omnisend');
    }

    /**
     * @Then I see omnisend product picker script with values:
     */
    public function iSeeOmnisendProductPickerScript(TableNode $table)
    {
        foreach ($table as $rowKey => $row) {
            $value = is_numeric($row['value']) ? $row['value'] : '"' . $row['value'] . '"';
            Assert::contains($this->homePage->getContent(), '"$' . $row['key'] .'":' . $value);
        }
    }
}
