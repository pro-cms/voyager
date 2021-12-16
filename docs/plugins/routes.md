# Routes


## Protected

Protected routes can only be accessed by users logged in to Voyager.  
To implement protected routes into your plugin implement the `ProtectedRoutes` trait and write a method `provideProtectedRoutes` registering your routes:


```php
<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Voyager\Admin\Contracts\Plugins\Features\Provider\ProtectedRoutes;

class MyPlugin implements ProtectedRoutes
{
    public function provideProtectedRoutes(): void
    {
        Route::get('/my-page', function () {
            return Inertia::render('component-to-render', [
                'foo'   => 'bar',
            ])->withViewData('title', 'My page');
        })->name('my-page');

        Route::post('/my-page', function () {
            // Do something
        })->name('my-page');
    }
}
```

::: info
This example shows an Inertia response that will show a Vue component inside Voyagers master-view.  
However, you can return whatever you want, a blade-view for example.
:::

## Frontend

Frontend routes can be accessed by everyone. You don't have to be logged in to Voyager to access those pages.  
To implement protected routes into your plugin implement the `FrontendRoutes` trait and write a method `provideFrontendRoutes` registering your routes:


```php
<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Voyager\Admin\Contracts\Plugins\Features\Provider\FrontendRoutes;

class MyPlugin implements FrontendRoutes
{
    public function provideFrontendRoutes(): void
    {
        Route::get('/my-page', function () {
            return view('my-view');
        })->name('my-page');

        Route::post('/my-page', function () {
            // Do something
        })->name('my-page');
    }
}
```

::: info
You are not able to display a component/view inside Voyagers master-view as this uses some data that is not available when the user is not logged-in.
::: info