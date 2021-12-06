# Menu Items

You can inject menu items to the menu by simply implementing the `MenuItems` provider and adding a method `provideMenuItems` to your plugin like this:

```php
<?php

namespace Me\MyPlugin;

use Voyager\Admin\Classes\MenuItem;
use Voyager\Admin\Contracts\Plugins\GenericPlugin;
use Voyager\Admin\Contracts\Plugins\Features\Provider\MenuItems;
use Voyager\Admin\Manager\Menu as MenuManager;

class MyPlugin implements GenericPlugin, MenuItems
{
    public function provideMenuItems(Menu $menuManager): void {
        $menumanager->addItems(
            (new MenuItem('My Title', 'icon'))->route('my-route')
        );
    }
}
```

You can also add a divider before or after your item like this:

```php
$menumanager->addItems(
    (new MenuItem())->divider(),
    (new MenuItem('My Title', 'icon'))->route('my-route')
);
```

## Available methods

| **Method**  | **Description**                                                  | Example                                                           | **Arguments**                                                                                      |
|-------------|------------------------------------------------------------------|-------------------------------------------------------------------|----------------------------------------------------------------------------------------------------|
| __construct | Creates a new Menu item                                          | `new MenuItem('My title');`                                       | string title: The title string icon: The name of an icon                                           |
| route       | A route to be used                                               | `->route('my.route.name')`                                        | string route: The route key array params: The parameters passed to the route                       |
| url         | A URL to be used                                                 | `->url('https://google.com')`                                     | string url: The URL                                                                                |
| permission  | Display/Hide the item based on a permission                      | `->permission('name_of_permission')`                              | string permission: The key of a permission, array args: Additional arguments                       |
| divider     | Acts as a divider between items                                  | `->divider()`                                                     | -                                                                                                  |
| exact       | Apply the active class only when the current URL matches exactly | `->exact()`                                                       | -                                                                                                  |
| badge       | Display a badge next to the title                                | `->badge('green', '10k+')` or `->badge('red')`                    | string color: Tailwind color of the badge (red, green, blue, ...), string value: The value or null |
| addChildren | Add children to the item                                         | `->addChildren(new MenuItem('Child 1'), new MenuItem('Child 2'))` | MenuItem item: One or many children                                                                |


## User dropdown

When you want to display menu items in the user dropdown simply use `Voyager\Admin\Classes\UserMenuItem` instead of `Voyager\Admin\Classes\MenuItem`.