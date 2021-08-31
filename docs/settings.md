# Settings

Settings are stored on your disk and can be version-controlled.

## Add a setting

To add a settings you have to select the group (or `No group`) in which you want to create your setting first. 
After that, hit `Add setting` and select the formfield type you want from the dropdown.

## Remove a setting

Click the `Trash` button and confirm that you want to delete this setting.

## Duplicate a setting

Click the `Layer` button to duplicate a setting.

## Generate the key for a setting

The key of a setting is basically the slugged version of the name. 
It is used to retreive the setting when calling `Voyager::setting()`. 
You can create this key by hitting the `Fingerprint` button, or by entering it manually.

## Move a setting

Click and hold the `Arrow` button and move the setting to your desired position.

## Change the group of a setting

Simply select the new group in the dropdown and it will be moved.

## Getting settings from code

To get all settings, call `Voyager::setting()` without any parameters. 
Use `Voyager::setting('my-group')` to get all settings in a group **or** a setting with this name without a group.  
And to get a single setting in a group call `Voyager::setting('group.name')`.

### Default value

Pass a second parameter with the default value to `Voyager::setting` to get this value when the setting does not exist.

### Translate

By default settings will be translated.  
To prevent this pass a third parameter as `false`.


## Writing settings from code

Use  `SettingsManager::set('key', 'value')` to set a non-translatable setting. 

Use `SettingsManager::set('key', ['en' => 'English value', 'de' => 'Deutscher Wert'])` to set multiple locales or `SettingsManager::set('key', 'English value', 'en')` to set a single locale


## Batch update

By default, when calling `SettingsManager::set(...)` the settings file will be stored on the disk.  
You can prevent this by passing an optional fourth parameter `save` as `false`.  
When you are done setting all your settings, you have to call `SettingsManager::save()`.