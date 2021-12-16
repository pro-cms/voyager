# Repeater

The repeater allows you to repeat a given set of other formfields.  
For example you could add a list with guests for your birthday party.  
First, you add a text formfield for the name, another text formfield for the telephone number and a checkbox wether the guest is attending or not.

![Repeater in the BREAD builder](/formfields/repeater/repeater-builder.png) 

This would result in the following when editing or adding an item:

![Repeater in a BREAD](/formfields/repeater/repeater-bread.png) 

And the following would be stored in our database:

```json
[
    {
        "name": "John Doe",
        "phone": "12345",
        "attending": ["yes"]
    }
]
```

## Options

### Type

Enter the name for an entry.    
In our example above this could be `Guest`.  
The type is used for the button `Add Guest` and the title (`Guest #1`).  
This field is translatable.

### Allow sorting

Allow your users to sort items.

## Browsing

Browsing only represent a simple text representation as we don't know about the formfields used in views

## Keys

When using **exactly** one formfield without a key, values will be stored as `['foo', 'bar', 'baz']`, otherwise as `[{ key: 'foo' }, { key: 'bar' }, { key: 'baz' }]`.  
Using multiple formfields without keys will show a warning.