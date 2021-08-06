# Formfields

Formfields are the heart of every layout.  
They display, parse and handle incoming data and input.

## Common options

### Column

Here you select which column this formfield consumes.  
This can be table columns, accessor, relationship properties or relationship methods.

### Translatable

If you want your data to be translatable, check this box.  
Please note that some formfields, like the relationship formfield, can not be translated.

### Title

The title shown at the top of the formfield.  
This field is translatable.

### Description

The description shown below the formfield.  
This field is translatable.

### Component

Here you can provide a name of a custom Vue component.  
Read more how to add components to Voyager [here](../../plugin-development/components.md)

### Classes

This input allows you to enter additional css classes applied to the parent formfield element.

### Validation

Here you enter all your validation rules and messages which wil be displayed when the rule fails.  
The message field is translatable.  
Learn more about validation [here](../../bread-builder/validation.md)

## Overview

This table gives you an overview of all built-in formfields and their recommended column type

| **Formfield**                       | **Description**                                      | **Recommended column type**                |
|-------------------------------------|------------------------------------------------------|--------------------------------------------|
| [Checkbox](checkbox.md)             | Check one or many given options                      | JSON*                                      |
| [Date & Time](datetime.md)          | Select date and/or time. Single or range             | Date, Timestamp, JSON*                     |
| [Dynamic input](dynamic-input.md)   | A dynamic form containing user generated data/inputs | Depending on your resulting key(s)         |
| [Media picker](media-picker.md)     | Select one or many files with the media manager      | JSON*                                      |
| [Number](number.md)                 | Enter a number, float or double                      | Int, Float, Double                         |
| [Password](password.md)             | A password formfield                                 | Text, Varchar                              |
| [Radio](radio.md)                   | Select one of many given options                     | Text, Number, ... (depending on your value)|
| [Relationship](relationship.md)     | Display a relationship                               | Depending on your resulting key(s)         |
| [Repeater](repeater.md)             | Display a repeatable set of formfields               | JSON*                                      |
| [Select](select.md)                 | Select one or multiple given options                 | Text, Varchar, JSON                        |
| [Simple array](simple-array.md)     | Enter multiple values of any kind                    | JSON*                                      |
| [Slider](slider.md)                 | Select a numeric value from a slider/range           | Int, JSON*                                 |
| [Slug](slug.md)                     | Generate a slug from a given formfield               | Text, Varchar                              |
| [Tags](tags.md)                     | Allows you to enter tags                             | JSON*                                      |
| [Text](text.md)                     | A standard text formfield                            | Text, Longtext, Varchar                    |


Formfields with an asterisk **require** the column to be real JSON as the result is always an array.