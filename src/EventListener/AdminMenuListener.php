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
            ->setLabel('nfq_sylius_omnisend_plugin.menu.admin.main.events');
    }
}
