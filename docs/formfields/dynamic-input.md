# Dynamic Input

The dynamic input is a simple yet powerful formfield allowing you to provide selects, number or text inputs, checkboxes, radios and switches based on your conditions/code.  
To retreive the possible options, Voyager will fetch a route you define in the formfield options.  
This route has to return a JSON object containing the set of fields you want to show.  
For easy use Voyager provides a class `VoyagerAdmin\Voyager\Classes\DynamicInput` that helps you create those responses.

## Available methods

### addSelect

Adds a select input to the formfield.  

| Parameter | Type                | Description                                                                    | Default |
|-----------|---------------------|--------------------------------------------------------------------------------|---------|
| key       | string, null        | The key in the final data                                                      | null    |
| title     | string, array, null | The title shown above the input                                                | null    |
| options   | array               | An array of key => value pairs. The key is used as the value in the final data | []      |
| multiple  | bool                | Allow multiple selection                                                       | false   |
| value     | mixed               | The value selected by the user or the default value                            | null    |

### addText

Add a text input to the formfield.

| Parameter   | Type                | Description                                                                    | Default |
|-------------|---------------------|--------------------------------------------------------------------------------|---------|
| key         | string, null        | The key in the final data                                                      | null    |
| title       | string, array, null | The title shown above the input                                                | null    |
| placeholder | string, array, null | An array of key => value pairs. The key is used as the value in the final data | null    |
| value       | mixed               | The value selected by the user or the default value                            | null    |

### addNumber

Add a number input to the formfield.

| Parameter   | Type                | Description                                                                    | Default |
|-------------|---------------------|--------------------------------------------------------------------------------|---------|
| key         | string, null        | The key in the final data                                                      | null    |
| title       | string, array, null | The title shown above the input                                                | null    |
| placeholder | string, array, null | An array of key => value pairs. The key is used as the value in the final data | null    |
| value       | int, null           | The value selected by the user or the default value                            | null    |
| min         | int, null           | The minimum value that can be entered. Null means negative infinite            | null    |
| max         | int, null           | The maximum value that can be entered. Null means infinite                     | null    |

### addCheckboxes

Adds multiple checkboxes to the formfield.  

| Parameter | Type                | Description                                                                    | Default |
|-----------|---------------------|--------------------------------------------------------------------------------|---------|
| key       | string, null        | The key in the final data                                                      | null    |
| title     | string, array, null | The title shown above the input                                                | null    |
| options   | array               | An array of key => value pairs. The key is used as the value in the final data | []      |
| value     | mixed               | The values selected by the user or the default value                           | null    |

### addRadios

Adds multiple radios to the formfield.  

| Parameter | Type                | Description                                                                    | Default |
|-----------|---------------------|--------------------------------------------------------------------------------|---------|
| key       | string, null        | The key in the final data                                                      | null    |
| title     | string, array, null | The title shown above the input                                                | null    |
| options   | array               | An array of key => value pairs. The key is used as the value in the final data | []      |
| value     | mixed               | The value selected by the user or the default value                            | null    |

### addSwitch

Add a simple switch to the formfield.

| Parameter   | Type                | Description                                                                    | Default |
|-------------|---------------------|--------------------------------------------------------------------------------|---------|
| key         | string, null        | The key in the final data                                                      | null    |
| title       | string, array, null | The title shown above the input                                                | null    |
| value       | mixed               | The value when the switch is active                                            | null    |

## Knowing the BREAD action

The incoming request contains a parameter `bread_action` which can be `query`, `browse`, `edit` or `add`.


## Using no keys on inputs

Sometimes its useful to use no key on an input.  
That way a single select with multiple=false will only store the selected value in the database instead of a key-value pair.  
But be aware that only one input total without a key can exist in a formfield.  
Otherwise an Exception `Only one input without a key can exist!` will be thrown.

## Examples

### Single select without a key

This example shows a single select with two options `English` and `German`.

```php
<?php

use VoyagerAdmin\Voyager\Classes\DynamicInput;

class MyController {
    public function getOptions() {
        return (new DynamicInput())->addSelect(null, 'Select a language', ['en' => 'English', 'de' => 'German']);
    }
}
```

The data stored in the database will be `en` or `de`.

### Single select with a key

This example shows a single select with two options `English` and `German`.

```php
<?php

use VoyagerAdmin\Voyager\Classes\DynamicInput;

class MyController {
    public function getOptions() {
        return (new DynamicInput())->addSelect('language', 'Select a language', ['en' => 'English', 'de' => 'German']);
    }
}
```

The data stored in the database will be `['language' => 'en']` or `['language' => 'de']`.

### Selects based in previous selection

In this example the user is asked to select a country first.  
Once the country is selected, another select is shown which allows the user to enter the state.

```php
<?php

use VoyagerAdmin\Voyager\Classes\DynamicInput;

class MyController {
    public function getOptions() {
        $country = $request->input('country');
        $input = (new DynamicInput())->addSelect('country', 'Select your country', ['us' => 'United States', 'de' => 'Germany']);

        if ($country == 'us') {
            $input->addSelect('state', 'Select your state', ['ny' => 'New York', 'wt' => 'Washington', /* ... */]);
        } elseif ($country == 'de') {
            $input->addSelect('state', 'Select your state', ['by' => 'Bavaria', 'ber' => 'Berlin', /* ... */]);
        }

        return $input;
    }
}
```

When selecting country `United States` and state `New York`, the data stored in the database will look like this:  
```json
{
    "country": "us",
    "state": "New York"
}
```