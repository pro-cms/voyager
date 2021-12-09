# Widgets

You can inject widgets which can be shown on the dashboard by simply implementing the `Widgets` provider and adding a method `provideWidgets` to your plugin like this:

```php
<?php

namespace Me\MyPlugin;

use Voyager\Admin\Classes\Widget;
use Voyager\Admin\Contracts\Plugins\GenericPlugin;
use Voyager\Admin\Contracts\Plugins\Features\Provider\Widgets;

class MyPlugin implements GenericPlugin, Widgets
{
    public function provideWidgets(): Collection {
        return collect([
            (new Widget('component-name', 'title'))->icon('academic-cap')
        ]);
    }
}
```

## Available methods

| **Method**  | **Description**                               | Example                                     | **Arguments**                                                                |   |
|-------------|-----------------------------------------------|---------------------------------------------|------------------------------------------------------------------------------|---|
| __construct | Creates a new Widget                          | `new Widget('component-name', 'My title');` | string component: the name of the component, string title: The title         |
| width       | Sets the width of the widget                  | `->width(6)`                                | int width: The width between 3 and 12                                        |
| icon        | An icon show next to the title                | `->icon('academic-cap')`                    | string icon: The name of the icon                                            |
| parameters  | Parameters passed to the component            | `->parameters(['key' => 'value'])`          | array parameters: The parameters                                             |
| permission  | Display/Hide the widget based on a permission | `->permission('name_of_permission')`        | string permission: The key of a permission, array args: Additional arguments |