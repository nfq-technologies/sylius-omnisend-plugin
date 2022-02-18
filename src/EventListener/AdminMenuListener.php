<?php

/*
 * This file is part of the NFQ package.
 *
 * (c) Nfq Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace NFQ\SyliusOmnisendPlugin\EventListener;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

class AdminMenuListener
{
    public function addAdminMenuItems(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        $submenu = $menu
            ->addChild('nfq_sylius_omnisend')
            ->setLabel('nfq_sylius_omnisend_plugin.menu.admin.main.omnisend');

        $submenu
            ->addChild(
                'nfq_sylius_omnisend_event',
                ['route' => 'nfq_sylius_omnisend_plugin_admin_event_index']
            )
            ->setLabel('nfq_sylius_omnisend_plugin.menu.admin.main.events')
            ->setLabelAttribute('icon', 'list');

        $submenu
            ->addChild(
                'nfq_sylius_omnisend_sync',
                ['route' => 'nfq_sylius_omnisend_sync_batch_index']
            )
            ->setLabel('nfq_sylius_omnisend_plugin.menu.admin.main.sync')
            ->setLabelAttribute('icon', 'refresh');
    }
}
