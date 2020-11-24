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

use Sylius\Behat\Page\Admin\Crud\IndexPage as BaseIndexPage;

class IndexPage extends BaseIndexPage
{
    public function startSyncProcess(): void
    {
        $button = $this->getDocument()->find('css', '#omnisend_sync_events');
        $button->click();
//        $button = $this->getElement('sync_button');
//        $button->click();
    }

    public function getItems(): array
    {
        return $this->getDocument()->findAll('css', 'tr.item');
    }

    protected function getDefinedElements(): array
    {
        return array_merge(
            parent::getDefinedElements(),
            [
                'sync_button' => '#omnisend_sync_events',
            ]
        );
    }
}
