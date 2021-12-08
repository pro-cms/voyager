# Components

To register a Vue component with Voyager, simply call `voyager.component('my-component', Component)`:  

```javascript

import Component from './Component.vue';
voyager.component('my-component', Component);

// Or
import { defineComponent } from 'vue';

voyager.component('my-component', defineComponent({
    data() {
        return {
            // ...
        };
    },
    methods: {
        // ...
    }
}));
```

## Settings

You can provide the name of a component that will be shown in a modal when clicking the `Settings` button on the plugins page.  
To do so, include the `SettingsComponent` trait and return the name of your component in a function named `getSettingsComponent`:


```php
<?php

use Voyager\Admin\Contracts\Plugins\Features\Provider\SettingsComponent;

class MyPlugin implements SettingsComponent
{
    public function getSettingsComponent(): string
    {
        return 'my-component-name';
    }
}
```