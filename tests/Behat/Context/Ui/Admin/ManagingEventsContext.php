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
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Tests\NFQ\SyliusOmnisendPlugin\Behat\Page\Admin\Event\CreatePage;
use Tests\NFQ\SyliusOmnisendPlugin\Behat\Page\Admin\Event\IndexPage;
use Tests\NFQ\SyliusOmnisendPlugin\Behat\Page\Admin\Event\UpdatePage;
use Tests\NFQ\SyliusOmnisendPlugin\Behat\Provider\CustomEventProvider;
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

    /** @var RepositoryInterface */
    private $repository;

    /** @var CustomEventProvider */
    private $customEventProvider;

    public function __construct(
        SharedStorageInterface $sharedStorage,
        CreatePage $createPage,
        UpdatePage $updatePage,
        IndexPage $indexPage,
        RepositoryInterface $repository,
        CustomEventProvider $customEventProvider
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->createPage = $createPage;
        $this->updatePage = $updatePage;
        $this->indexPage = $indexPage;
        $this->repository = $repository;
        $this->customEventProvider = $customEventProvider;
    }

    /** @Given I want to create a new custom event */
    public function iWantToCreateANewTaxon(): void
    {
        $this->createPage->open();
    }

    /** @Given I view custom Omnisend event list */
    public function iViewEventsList(): void
    {
        $this->indexPage->open();
    }

    /** @When I initialize sync process */
    public function iInitializeSyncProcess(): void
    {
        $this->indexPage->startSyncProcess();
    }

    /** @Then I should see one item in the list */
    public function iShouldSeeItemInList(): void
    {
        Assert::count($this->indexPage->getItems(), 1);
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
    /**
     * @When I update it
     */
    public function iUpdateIt()
    {
        $this->updatePage->saveChanges();
    }

    /** @Then I should be notified that form contains errors */
    public function iShouldBeNotifiedItHasBeenSuccessfullyCreated()
    {
        Assert::notNull($this->createPage->getFormError());
    }

    /**
     * @When I want to modify the :systemName event
     */
    public function iWantToModifyAProduct(string $systemName): void
    {
        $event = $this->repository->findOneBy(['systemName' => $systemName]);

        if (null !== $event) {
            $this->updatePage->open(['id' => $event->getId()]);
        }
    }
}
