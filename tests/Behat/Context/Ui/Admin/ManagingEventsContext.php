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

namespace Tests\NFQ\SyliusOmnisendPlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Sylius\Behat\NotificationType;
use Sylius\Behat\Service\SharedStorageInterface;
use Tests\NFQ\SyliusOmnisendPlugin\Behat\Page\Admin\Event\CreatePage;
use Tests\NFQ\SyliusOmnisendPlugin\Behat\Page\Admin\Event\IndexPage;
use Tests\NFQ\SyliusOmnisendPlugin\Behat\Page\Admin\Event\UpdatePage;
use Webmozart\Assert\Assert;

class ManagingEventsContext implements Context
{
    /** @var SharedStorageInterface */
    private $sharedStorage;

    /** @var CreatePage */
    private $createPage;

    /** @var UpdatePage */
    private $updatePage;

    /** @var IndexPage */
    private $indexPage;

    public function __construct(
        SharedStorageInterface $sharedStorage,
        CreatePage $createPage,
        UpdatePage $updatePage,
        IndexPage $indexPage
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->createPage = $createPage;
        $this->updatePage = $updatePage;
        $this->indexPage = $indexPage;
    }

    /** @Given I want to create a new custom event */
    public function iWantToCreateANewTaxon(): void
    {
        $this->createPage->open();
    }

    /** @When I specify its system name as :name */
    public function iSpecifyItsSystemNameAs(string $name): void
    {
        $this->createPage->setSystemName($name);
    }

    /** @When I add new custom field */
    public function iAddNewField(): void
    {
        $this->createPage->addNewField();
    }

    /** @When I fill last added custom field system name with value :value */
    public function iFillLastAddedFieldSystemNameWithValue(string $value): void
    {
        $this->createPage->setLastAddedItemSystemName($value);
    }

    /** @When I select last added field type :type */
    public function iSelectLastAddedFieldType(string $type): void
    {
        $this->createPage->setLastAddedItemType($type);
    }

    /**
     * @When I add it
     * @When I try to add it
     */
    public function iAddIt()
    {
        $this->createPage->create();
    }

    /** @Then I should be notified that form contains errors */
    public function iShouldBeNotifiedItHasBeenSuccessfullyCreated()
    {
        Assert::notNull($this->createPage->getFormError());
    }
}
