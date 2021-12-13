# Javascript

This page covers some of the publicy available APIs Voyager exposes for you to use.

## Notifications

Allows you to fluently display notifications in the UI.

### Simple

```javascript
new $this.notification('Title').show();
```

### Title and message

```javascript
new $this.notification('Title').message('Message').show();
```

### Timeout

```javascript
new $this.notification('Title').timeout().show(); // 7500ms timeout, or:
new $this.notification('Title').timeout(5000).show(); // Custom (5000ms) timeout
```

### Indeterminate

```javascript
new $this.notification('Title').indeterminate().show();
```

### Confirm

```javascript
new $this.notification('Title').confirm().show().then((result) => { /* result is boolean */ });
```

### Prompt

```javascript
new $this.notification('Title').prompt('Value').show().then((result) => { /* result is the entered text or false */ });
```

### Select

```javascript
new $this.notification('Title').select({ key: 'Value' }).show().then((result) => { /* result is a key or false */ });
```

### Methods

| **Method**  | **Description**                                                                                                                                     | **Arguments**                                                                                                                                                               |
|-------------|-----------------------------------------------------------------------------------------------------------------------------------------------------|-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| constructor | Creates a new notification                                                                                                                          | title: string, the title                                                                                                                                                    |
| title       | Sets the title of a notification                                                                                                                    | title: string, the title                                                                                                                                                    |
| message     | Sets the message of a notification                                                                                                                  | message: string, the message                                                                                                                                                |
| icon        | Sets the icon of the notification                                                                                                                   | icon: string, the name of an icon                                                                                                                                           |
| color       | Sets the color of the notification                                                                                                                  | color: string, any of the available colors                                                                                                                                  |
| timeout     | Sets the timeout after which the notification will automatically close                                                                              | timeout: ?int, number of ms after which the notification will close. Dont pass this arguments to timeout after 7500ms                                                       |
| indetermine | Shows an infinite bar                                                                                                                               | None                                                                                                                                                                        |
| prompt      | Shows an input box. Automatically adds buttons when no other exist                                                                                  | value: string, the default value or a variable that will be modified                                                                                                        |
| confirm     | Shows a `Yes` and a `No` button                                                                                                                     | None                                                                                                                                                                        |
| select      | Show a select box. Automatically adds buttons when no other exist                                                                                   | options: Object, the options as a key: value pair. Use this method multiple times to add multiple selects.                                                                  |
| addButton   | Add a button to the notification. When this method is called before calling `confirm` , `select` or `prompt` it will override the standard buttons. | button: Object, Button data containing: `key` will be passed to the promise `value` will be shown as button text (can be a translation key) `color` the color of the button |
| show        | Show the notification. If `confirm`, `prompt` or `select` is used, will return a `Promise`                                                          | None                                                                                                                                                                        |

## Store

Voyager implements a very simple store that holds some of the most important data used throughout of Voyager.  
This includes BREADs, menu items, tables, formfields and much more.  
Check the whole store [here](https://github.com/voyager-admin/voyager/blob/2.x/resources/assets/js/store.ts)

In your Vue components you are able to access the store as easy as:

```javascript
export default {
    mounted() {
        console.log(`You are logged in as ${this.$store.user.name}`);
    }
}
```

## Eventbus

Voyager fires various events and listenes to them in another place.  
For example, when updating the `admin.sidebar_title` setting, it will automatically update while you are typing.  
To fire and listen to your own events, use the following example:

```javascript
export default {
    methods: {
        click() {
            this.$eventbus.emit('my-event', 'My payload');
        }
    },
    mounted() {
        this.$eventbus.on('my-event', (e) {
            console.log(e);
        });
    }
}
```

To only listen for an event (`setting-updated` in this example):

```javascript
export default {
    mounted() {
        this.$eventbus.on('setting-updated', (setting) {
            console.log(`Setting ${setting.group}.${setting.key} has been updated to: ${setting.value}`);
        });
    }
}
```

## Slugify

Voyager uses [slugify](https://github.com/simov/slugify) under the hood.  
Check out its README to learn about its usage.

```javascript
export default {
    mounted() {
        console.log(${this.$slugify('A not slugged string!')});
    }
}
```

## Axios

Voyager uses [axios](https://axios-http.com/) to make Ajax requests.  
To use axios in your own code, use:

```javascript
import axios from 'axios';

export default {
    methods: {
        load() {
            axios.post(...);
        }
    }
}
```

Please read [this](#a-note-on-vite) to found out how to import axios without installing it locally.

## Debounce

To use [debounce](https://github.com/component/debounce) simply use:

```javascript
export default {
    methods: {
        load() {
            this.$debounce(() => {
                console.log('I am debounced');
            });
        }
    }
}
```

## A note on Vite

Axios and Vue are exposed to the browsers `window` object.  
This allows you to use those plugins in your code without installing them locally and thus reducing bundle size.  
In your `vite.config.js` you can use the following to exclude them from being bundled:

```javascript
export default defineConfig({
    rollupOptions: {
        external: ['vue', 'axios'],
        output: {
            globals: {
                vue: 'Vue',
                axios: 'axios',
            }
        }
    }
})
```


The rest of the methods/APIs are set into Vues `globalProperties`.