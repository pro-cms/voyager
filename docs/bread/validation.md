# Validation

Voyager allows you to dynamically add validation rules to all formfields in a view.  
To start open the options for a formfield and look for the `Validation` section.  
Click the `+` button to add a rule and fill in the `Rule` and the `Message` which will be displayed when this rule fails. 
The message field is translatable to display translated error messages to users.

![](./.gitbook/assets/bread-builder/validation.png) 

Please check the [Laravel documentation](https://laravel.com/docs/validation#available-validation-rules) for all available validation rules.

## Validating multi language inputs

You can validate the current app locale **or** all locales by setting the option to `Validate all locales` or `Validate current locale` in the layout options.  
With this you can use `Voyager::setLocales([...])` to dynamically set languages for various users.  
For example user A speaks english and german, you could run
```php
Voyager::setLocales(['de', 'en']);
```
in your service provider and select `Validate all locales` to force the user to enter the data in both his languages. 

When `Validate current locale` is selected, only the current locale is validated. Other entered languages are **ignored**.

## Validating array elements

### Validating 1-dimensional arrays

Given then following array:

```php
[
    'name'  => 'admin',
    'email' => 'foo@bar.baz',
]
```

you can validate any fields by using `.name:required` or `.email:email` respectively.  
Please notice the leading `.` which tells Voyager that you want to validate a 1-dimensional array

### Multi-dimensional array

Given the following array of users:

```php
[
    [
        'name'  => 'Admin',
        'email' => 'foo@bar.baz',
    ],
    [
        'name'  => 'User',
        'email' => 'e@ma.il',
    ],
]
```

You can validate any fields in **all** elements (users in this case) by using `*.name:required` and `*.email:email`.  
Please notice the leading `*.` which tells Voyager that you want to validate a multi-dimensional array