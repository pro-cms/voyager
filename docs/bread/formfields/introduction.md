# Formfields

Formfields are the heart of every layout.  
They display, parse and handle incoming data and input.

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