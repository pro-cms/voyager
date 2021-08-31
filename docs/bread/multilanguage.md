# Multilanguage

Voyager was built with multi-language support in mind. 
Translatable fields will be stored as JSON in your database.  
In order to query (search) for translated values, Voyager uses the JSON SQL syntax and therefore needs _real_ JSON columns to function properly.


## Configuring your model

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use VoyagerAdmin\Voyager\Traits\Translatable;

class User extends Model
{
    use Translatable;

    public $translatable = ['my_field'];
}
```

## Getting translated values

To get the translated value of a field in the default locale, you can simply access the property:

`$myModel->my_field`

To get the value in a locale which is not the default call:

`$myModel->getTranslated('my_field', 'de', 'en', 'Default value')`

The first parameter is the field you want to access, the second the locale you want to get the value in, the third the fallback locale if a translation does not exist in the wanted locale and the firth parameter is the value which will be returned when none of the locales exist.

## Setting translated values

The current locale of a property can be set as normal:

`$myModel->my_field = 'New value'`

To set another locale, call:

`$myModel->setTranslated('my_field', 'Neuer Wert', 'de')`

## Do not automatically translate

Sometimes it is useful to not automatically translate properties when accessing them. 
This can be done by calling `dontTranslate()` on your model. 
To re-activate automatic translations, call `translate()`. 
After calling `dontTranslate()` you will get an array containing locale => value pairs, for example:

```
[
    "en" => "Value",
    "de" => "Wert"
]
```