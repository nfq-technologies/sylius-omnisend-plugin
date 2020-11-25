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
