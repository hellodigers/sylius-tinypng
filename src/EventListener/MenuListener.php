<?php

declare(strict_types=1);

namespace Dige\TinypngPlugin\EventListener;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

class MenuListener
{
    public function buildMenu(MenuBuilderEvent $menuBuilderEvent): void
    {
        $menu = $menuBuilderEvent->getMenu();
        $cmsRootMenuItem =
            $menu
                ->addChild('tinypng')
                ->setLabel('tinypng.ui.menu.label');

        $cmsRootMenuItem
            ->addChild('tinypng_settings', [
                'route' => 'dige_sylius_tinypng_plugin_settings',
            ])
            ->setLabel('tinypng.ui.menu.settings')
            ->setLabelAttribute('icon', 'tags');
    }
}
