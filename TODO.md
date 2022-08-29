This Todo list does **not** contain big improvements.  
It only contains small things that came to our minds which we couldn't implement immediately and would be forgotten otherwise.  
Instead, they are listed here:

- Test everything on mobile (especially media manager)
- Modal should show scrollbar inside container, not outside
- Media manager: While uploading files, they are shown in every folder
- Media manager: When navigating through folders, it is not possible to use browser-back
- Media manager: X and Y offset for watermarks should be percentage(?)
- Test (download) actions
- Catch some common errors (Not a JSON column, column can not be null, Column does not exist, Prop is hidden, ...)
- Rethink what buttons are shown when a BREAD was stored. Directly allow to update?
- When querying a xMany relationship, it should only display those
- Let dynamic input provide validation rules
- When a BREAD slug is empty (translated) it will override the `voyager.dashboard` route and make the whole admin panel break
- Multiple timeout notifications don't close properly (timeout bar disappears)
- Make list filters draggable
- Sanitize formfields where a column does not exist (anymore)
- Improve media lightbox
- Make thumbnail hiding work again

## Formfields testing

- [X] Checkbox
- [ ] Date/Time picker
- [ ] Dynamic Input
- [ ] MediaPicker
- [X] Number
- [X] Password
- [X] Radio
- [X] Select
- [X] SimpleArray
- [X] Slider
- [X] Slug
- [X] Tags
- [X] Text
- [X] Toggle

## Documentation
- Scopes need to start with `scope` (ex. `scopeCurrentUser()`)
- Accessors need to be named `getFieldAttribute` (ex. `getFullNameAttribute`)
- Computed properties need to implement an accessor AND mutator when used for adding or editing
- Ordering only works when there are actual values in the field. Those have to be set by a mutator (or similar)