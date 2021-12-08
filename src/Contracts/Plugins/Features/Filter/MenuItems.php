<?php

namespace Voyager\Admin\Contracts\Plugins\Features\Filter;

use Illuminate\Support\Collection;

/**
 * An interface for plugins that want to filter menu items.
 */
interface MenuItems
{
    /**
    * Filter menu items.
    *
    * @param Collection $items    The menu items
    * @param boolean    $mainMenu When true items for the main-menu are passed, when false for the user-menu
    *
    * @return Collection The filtered menu items
    */
    public function filterMenuItems(Collection $items, $mainMenu = true): Collection;
}