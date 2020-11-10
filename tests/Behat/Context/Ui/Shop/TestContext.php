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
use Tests\NFQ\SyliusOmnisendPlugin\Behat\Page\Shop\TestPage;
use Webmozart\Assert\Assert;

final class TestContext implements Context
{
    private TestPage $testPage;

    public function __construct(TestPage $testPage)
    {
        $this->testPage = $testPage;
    }

    /**
     * @When I visit a test plugin page
     */
    public function iVisitTestPluginPage(): void
    {
        $this->testPage->open();
    }

    /**
     * @When I should see plugin name
     */
    public function iSeePluginName(): void
    {
        Assert::same('Omnisend', $this->testPage->getContent());
    }
}
