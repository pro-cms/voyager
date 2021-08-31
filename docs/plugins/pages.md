# Custom pages

This page shows how to add a custom page with a component only to logged-in users.  
You need to define and register a [component](./components.md) and add a [menu-item](./menu-items.md) to Voyager if you want.

```php
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Voyager\Admin\Contracts\Plugins\Features\Provider\ProtectedRoutes;

class MyPlugin implements ProtectedRoutes
{
    public function provideProtectedRoutes(): void
    {
        Route::get('my-page', function (Request $request) {
            // Trigger Voyager to inject some necessary dynamic data
            Inertia::setRootView('voyager::app');

            return Inertia::render('my-component', [
                'data' => []
            ])->withViewData('title', 'My page');
        });
    }
}
```