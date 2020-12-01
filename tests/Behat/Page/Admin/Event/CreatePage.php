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

namespace Tests\NFQ\SyliusOmnisendPlugin\Behat\Page\Admin\Event;

use Sylius\Behat\Page\Admin\Crud\CreatePage as BaseCreatePage;

class CreatePage extends BaseCreatePage
{
    public function setSystemName(string $key): void
    {
        $this->getElement('system_name')->setValue($key);
    }

    public function addNewField(): void
    {
        $button = $this->getDocument()->find('css', '[data-form-collection="add"]');
        $button->click();
    }

    public function setLastAddedItemSystemName(string $value): void
    {
        $this->getDocument()->find('css', '[data-form-collection="item"]:last-child input')->setValue($value);
    }

    public function setLastAddedItemType(string $value): void
    {
        $this->getDocument()->find('css', '[data-form-collection="item"]:last-child select')->setValue($value);
    }

    public function getFormError(): ?string
    {
        return $this->getDocument()->find('css', '.ui.icon.negative.message')->getText();
    }

    protected function getDefinedElements(): array
    {
        return array_merge(
            parent::getDefinedElements(),
            [
                'system_name' => '#event_systemName',
            ]
        );
    }
}
