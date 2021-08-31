# Plugin development

This section shows you how to develop plugins which can be used in Voyager.

## Basics

Each plugin requires some steps to be recognized by Voyager.  


**Service Provider**  
Registers the plugin(s):

```php
<?php

namespace My\Plugin;

use Illuminate\Support\ServiceProvider;
use Voyager\Admin\Manager\Plugins as PluginManager;

class MyPluginServiceProvider extends ServiceProvider
{
    public function boot(PluginManager $pluginmanager)
    {
        $pluginmanager->addPlugin(\My\Plugin\MyPlugin::class);
    }
}
```

{% hint style="info" %}
One package can provide multiple plugins.  
For example, a plugin could provide multiple widgets or even different types of plugins like a theme and widgets.  
All plugins can be enabled/disabled independently. Make sure they don't depend on each other!
{% endhint %}

**Plugin class**

The plugin class represents the actual plugin and its methods:

```php
<?php

namespace My\Plugin;

use Voyager\Admin\Contracts\Plugins\GenericPlugin;

class MyPlugin implements GenericPlugin
{
    public $name = 'My plugin';
    public $description = 'This is my plugin!';
    public $repository = 'my/plugin';
    public $website = 'https://github.com/my/plugin';

    // Methods depending on your plugin-type, providers and filters.
}
```

**composer.json**

To be able to find your plugin through Voyagers UI you have to provide the tag `voyager2-plugin` in your composer.json file:

```json
{
    "keywords": ["voyager2-plugin"],
}
```

## Types

Plugins can be of various types:

- **Authentication** handles authentication of users in Voyager (e.g. login, signup, etc)
- **Authorization** handles permissions for users/actions
- **Formfield** provides a formfield
- **Generic** a plugin that doesn't fit the other types
- **Theme** provides a custom theme
- **Widget** provides a widget

## Providers

Voyager uses provider traits to provide various things. 
Those are:

- **CSS** to provide your custom css throughout Voyager (as a string)
- **FrontendRoutes** provide additional routes accessible by everyone
- **InstructionsComponent** a component that can be displayed in the plugins UI to give the user some instructions (your component name as a string)
- **JS** provide custom js throughout Voyager (as a string)
- **ProtectedRoutes** register protected routes that can only be accessed by users signed-in to Voyager
- **Settings** register additional settings to be stored in `settings.json`
- **SettingsComponent** a component that can be displayed in the plugins UI to set some settings depending on your needs (your component name as a string)

## Filter

Filter allow a plugin to filter and manipulate various data displayed in Voyager:

- **Layouts** the layouts for a BREAD
- **MenuItems** the menu items shown in the menu bar
- **Widgets** the widgets displayed to the user

## Templates

We created templates for all types of plugins on Github to get you started easily:
- Authentication (WIP)
- Authorization (WIP)
- [Formfield](https://github.com/voyager-admin/formfield-boilerplate)
- [Generic](https://github.com/voyager-admin/generic-boilerplate)
- [Theme](https://github.com/voyager-admin/theme-boilerplate)
- [Widget](https://github.com/voyager-admin/widget-boilerplate)