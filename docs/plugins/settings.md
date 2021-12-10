# Settings

A plugin can provide settings.  
Those settings will be merged in the existing settings and will be stored in `settings.json` only when the user hits `Save` on the settings-page.  
Always make sure to use default values when accessing them!  
To provide additional settings implement the `Settings` provider and add a method `provideSettings` like this:

```php
use Voyager\Admin\Contracts\Plugins\GenericPlugin;
use Voyager\Admin\Contracts\Plugins\Features\Provider\Settings;

class MyPlugin implements GenericPlugin, Settings
{
    public function provideSettings(): array
    {
        return [
            [
                'type'          => 'text',
                'group'         => 'My group',
                'name'          => 'My setting',
                'key'           => 'my_setting',
                'value'         => 'Value',
                'translatable'  => false,
                'info'          => 'This is a setting provided by a plugin',
                'options'       => [],
                'validation'    => [],
            ],
            [
                'type'          => 'text',
                'group'         => 'My group',
                'name'          => 'My second setting',
                'key'           => 'my_second_setting',
                'value'         => 'Value',
                'translatable'  => false,
                'info'          => 'This is another setting provided by a plugin',
                'options'       => [],
                'validation'    => [],
            ]
        ];
    }
}
```

{% hint style="info" %}
Make sure to always return an array containing settings (as an array).  
The best way to generate this setting is to simply create it through the UI and then copy/paste it from your `settings.json` file.
{% endhint %}

{% hint style="warning" %}
Whenever a setting with a group and key already exists, your provided setting will be ignored!
{% endhint %}