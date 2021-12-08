# Filters

Filters allow you to manipulate and filter various things used in Voyager.

## Layouts

Filter the available layouts for a given BREAD.

```php
<?php

use Illuminate\Support\Collection;
use Voyager\Admin\Classes\Bread;
use Voyager\Admin\Contracts\Plugins\GenericPlugin;
use Voyager\Admin\Contracts\Plugins\Features\Filter\Layouts as LayoutFilter;

class MyPlugin implements GenericPlugin, LayoutFilter
{
    public function filterLayouts(Bread $bread, string $action, Collection $layouts): Collection
    {
        // $bread contains the BREAD class
        // $action can be either "browse", "read", "edit" or "add"

        return $layouts->filter(function ($layout) {
            // Add your conditions here
            return true;
        });
    }
}
```

## MenuItems

Filter the shown menu-items.

```php
<?php

use Illuminate\Support\Collection;
use Voyager\Admin\Contracts\Plugins\GenericPlugin;
use Voyager\Admin\Contracts\Plugins\Features\Filter\MenuItems as MenuItemFilter;

class MyPlugin implements GenericPlugin, MenuItemFilter
{
    public function filterMenuItems(Collection $items, $mainMenu = true): Collection
    {
        // $mainMenu is true when items for the main-menu are passed, when false for the user-menu
        return $items->filter(function ($item) {
            // Add your conditions here
            return true;
        });
    }
}
```

## Widgets

Filter the widgets shown on the dashboard

```php
<?php

use Illuminate\Support\Collection;
use Voyager\Admin\Contracts\Plugins\GenericPlugin;
use Voyager\Admin\Contracts\Plugins\Features\Filter\Widgets as WidgetFilter;

class MyPlugin implements GenericPlugin, WidgetFilter
{
    public function filterWidgets(Collection $widgets): Collection
    {
        return $widgets->filter(function ($widget) {
            // Add your conditions here
            return true;
        });
    }
}
```

## Media

Filter the shown files/directories in the media-manager

```php
<?php

use Illuminate\Support\Collection;
use Voyager\Admin\Contracts\Plugins\GenericPlugin;
use Voyager\Admin\Contracts\Plugins\Features\Filter\Media as MediaFilter;

class MyPlugin implements GenericPlugin, MediaFilter
{
    public function filterMedia(Collection $files): Collection
    {
        return $files->filter(function ($file) {
            // Add your conditions here
            return true;
        });
    }
}
```