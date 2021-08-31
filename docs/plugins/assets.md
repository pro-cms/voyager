# Assets

Chances are very high that you want your formfield to be able to provide Javascript and/or CSS.  
This can be done by implementing a contract and providing a method returning your assets as plain text.   
Directly providing your assets allows you to simply develop your plugin and releasing it - without the need to re-publish files with any change.  
Don't worry - Voyager takes care of caching your assets.

## Javascript

Implement the contract `Voyager\Admin\Contracts\Features\Features\Provider\JS` and provide a method `provideJS()` returning a string containing your Javascript code.

{% hint style="info" %}
A formfield automatically implements the JS contract.  
You don't need to do this manually!
{% endhint %}

## CSS

Implement the contract `Voyager\Admin\Contracts\Features\Features\Provider\CSS` and provide a method `provideCSS()` returning a string containing your CSS.

## Example

```php
use Voyager\Admin\Contracts\Plugins\GenericPlugin;
use Voyager\Admin\Contracts\Plugins\Features\Provider\CSS;
use Voyager\Admin\Contracts\Plugins\Features\Provider\JS;

class VoyagerDocs implements GenericPlugin, CSS, JS
{
    // ...

    public function provideCSS(): string
    {
        return file_get_contents('path/to/your/asset.css');
    }

    public function provideJS(): string
    {
        return file_get_contents('path/to/your/asset.js');
    }
}
```