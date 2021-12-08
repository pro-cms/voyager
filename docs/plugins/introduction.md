# Plugin development

This section shows you how to develop plugins which can be used in Voyager.

## Setting up your development environment

This chapter shows you the easiest way to develop your plugin.


Choose one of the [templates](#templates) we provide and create a repository from it.
Open `composer.json` and change `name` to whatever you want.  
Next, push your changes to Github.  
Now you are ready to require your package to your base Laravel installation.  
Go to your Laravel installation, open `composer.json` and add the following:

```json
"minimum-stability": "dev",
"require": {
    "your/name": "*"
},
"repositories": [
    {
        "type": "path",
        "url": "path/to/your/plugin"
    }
]
```

`your/name` is the name you used in the `composer.json` file of your plugin.  
Next run `composer update` in your laravel installation.  
After that you are able to simply reload your page and immediately see any changes you made.

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

    public function __construct() {
        // Optionally provide a README file that can be displayed in the plugin UI
        $this->readme = realpath(dirname(__DIR__, 1).'/README.md');
    }
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

| **Type**       | **Class**                                                                                                                                          | **Description**                                                            |
|----------------|----------------------------------------------------------------------------------------------------------------------------------------------------|----------------------------------------------------------------------------|
| Authentication | [\Voyager\Admin\Contracts\Plugins\AuthenticationPlugin](https://github.com/voyager-admin/voyager/blob/2.x/src/Contracts/Plugins/AuthenticationPlugin.php) | Handles authentication of users inside Voyager (login, password reset etc) |
| Authorization  | [\Voyager\Admin\Contracts\Plugins\AuthorizationPlugin](https://github.com/voyager-admin/voyager/blob/2.x/src/Contracts/Plugins/AuthorizationPlugin.php)                                                                                              | Handles permissions for users and actions                                  |
| Formfield      | [\Voyager\Admin\Contracts\Plugins\FormfieldPlugin](https://github.com/voyager-admin/voyager/blob/2.x/src/Contracts/Plugins/FormfieldPlugin.php)                                                                                               | Provides one or many formfields                                            |
| Generic        | [\Voyager\Admin\Contracts\Plugins\GenericPlugin](https://github.com/voyager-admin/voyager/blob/2.x/src/Contracts/Plugins/GenericPlugin.php)                                                                                                     | A plugin that doesn't fit the other types                                  |
| Theme          | [\Voyager\Admin\Contracts\Plugins\ThemePlugin](https://github.com/voyager-admin/voyager/blob/2.x/src/Contracts/Plugins/ThemePlugin.php)                                                                                                       | Provides one or many themes                                                |
| Widget         | [\Voyager\Admin\Contrcts\Plugins\WidgetPlugin](https://github.com/voyager-admin/voyager/blob/2.x/src/Contracts/Plugins/WidgetPlugin.php)                                                                                                       | Provides one or many widgets                                               |

Each type has individual methods you have to implement in your plugin class.  
Check the Github link to find out more about those methods.

{% hint style="info" %}
Because the plugin type classes are interfaces you can implement multiple types in one plugin!
{% endhint %}

## Providers

Voyager uses provider traits to provide various things. 
Those are:

| **Type**          | **Class**                                                           | **Description**                                                              | **Documentation**                |
|-------------------|---------------------------------------------------------------------|------------------------------------------------------------------------------|----------------------------------|
| CSS               | Voyager\Admin\Contracts\Plugins\Features\Provider\CSS               | Provide CSS throughout Voyager                                               | [Here](./assets.md#css)          |
| FrontendRoutes    | Voyager\Admin\Contracts\Plugins\Features\Provider\FrontendRoutes    | Register additional routes accessible by everyone                            | [Here](./routes.md#frontend)     |
| JS                | Voyager\Admin\Contracts\Plugins\Features\Provider\JS                | Provide JavaScript throughout Voyager                                        | [Here](./assets.md#js)           |
| MenuItems         | Voyager\Admin\Contracts\Plugins\Features\Provider\MenuItems         | Provide menu-items to be shown in the sidebar                                | [Here](./menu-items.md)          |
| ProtectedRoutes   | Voyager\Admin\Contracts\Plugins\Features\Provider\ProtectedRoutes   | Register additional routes only accessible by users logged-in to Voyager     | [Here](./routes.md#protected)    |
| Settings          | Voyager\Admin\Contracts\Plugins\Features\Provider\Settings          | Register additional settings to be stored in settings.json                   | [Here](./settings.md)            |
| SettingsComponent | Voyager\Admin\Contracts\Plugins\Features\Provider\SettingsComponent | Name of a component to be used when clicking `Settings` in the plugins table | [Here](./components.md#settings) |



## Filter

Filter allow a plugin to filter and manipulate various data displayed in Voyager:

| **Type**  | **Class**                                                 | **Description**                              | **Documentation**              |
|-----------|-----------------------------------------------------------|----------------------------------------------|--------------------------------|
| Layouts   | Voyager\Admin\Contracts\Plugins\Features\Filter\Layouts   | Filter the layouts for a given BREAD         | [Here](./filter.md#layouts)    |
| MenuItems | Voyager\Admin\Contracts\Plugins\Features\Filter\MenuItems | Filter menu-items for the main and user-menu | [Here](./filter.md#menuitems)  |
| Widgets   | Voyager\Admin\Contracts\Plugins\Features\Filter\Widgets   | Filter widgets shown on the dashboard        | [Here](./filter.md#widgets)    |
| Media     | Voyager\Admin\Contracts\Plugins\Features\Filter\Media     | Filter media files in the current directory  | [Here](./filter.md#media)      |


## Templates

We created templates for all types of plugins on Github to get you started easily:

| Type           | Link                                                        |
|----------------|-------------------------------------------------------------|
| Authentication | https://github.com/voyager-admin/authentication-boilerplate |
| Authorization  | https://github.com/voyager-admin/authorization-boilerplate  |
| Formfield      | https://github.com/voyager-admin/formfield-boilerplate      |
| Generic        | https://github.com/voyager-admin/generic-boilerplate        |
| Theme          | https://github.com/voyager-admin/theme-boilerplate          |
| Widget         | https://github.com/voyager-admin/widget-boilerplate         |
