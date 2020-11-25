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
