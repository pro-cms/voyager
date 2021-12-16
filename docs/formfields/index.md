# Formfields

Formfields are the heart of every layout.  
They display, parse and handle incoming data and input.

## Common options

### Column

Here you select which column this formfield consumes.  
This can be table columns, accessor, relationship properties or relationship methods. 
Please be aware that (most of the time) the column is necessary.  
When saving the BREAD with a formfield that does not have a column assigned, a warning will be shown.

### Translatable

If you want your data to be translatable, check this box.  
Please note that some formfields, like the relationship formfield, can not be translated.In this case the checkbox is not shown.   
Read more about multilanguage [here](../bread/multilanguage)

### Title

The title shown above the formfield.  
This field is translatable.

### Description

The description shown below the formfield.  
This field is translatable.

### Component

Here you can provide a name of a custom Vue component.  
Read more how to add components to Voyager [here](../plugins/components)

### Classes

This input allows you to enter additional CSS classes applied to the parent formfield element.

### Validation

Here you enter all your validation rules and messages which wil be displayed when the rule fails.  
The message field is translatable.  
Learn more about validation [here](../bread/validation)

## Overview

This table gives you an overview of all built-in formfields and their recommended column type

| **Formfield**                       | **Description**                                      | **Recommended column type**                |
|-------------------------------------|------------------------------------------------------|--------------------------------------------|
| [Checkboxes](checkboxes)            | Check one or many given options                      | JSON*                                      |
| [Date & Time](datetime)             | Select date and/or time. Single or range             | Date, Timestamp, JSON*                     |
| [Dynamic input](dynamic-input)      | A dynamic form containing user generated data/inputs | Depending on your resulting key(s)         |
| [Media picker](media-picker)        | Select one or many files with the media manager      | JSON*                                      |
| [Number](number)                    | Enter a number, float or double                      | Int, Float, Double                         |
| [Password](password)                | A password formfield                                 | Text, Varchar                              |
| [Radios](radios)                    | Select one of many given options                     | Text, Number, ... (depending on your value)|
| [Relationship](relationship)        | Display a relationship                               | Depending on your resulting key(s)         |
| [Repeater](repeater)                | Display a repeatable set of formfields               | JSON*                                      |
| [Select](select)                    | Select one or multiple given options                 | Text, Varchar, JSON                        |
| [Simple array](simple-array)        | Enter multiple values of any kind                    | JSON*                                      |
| [Slider](slider)                    | Select a numeric value from a slider/range           | Int, JSON*                                 |
| [Slug](slug)                        | Generate a slug from a given formfield               | Text, Varchar                              |
| [Tags](tags)                        | Allows you to enter tags                             | JSON*                                      |
| [Text](text)                        | A standard text formfield                            | Text, Longtext, Varchar                    |
| [Toggle](toggle)                    | A binary switch                                      | Varchar, Integer, Binary                   |


Formfields with an asterisk **require** the column to be real JSON as the result is always an array.