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