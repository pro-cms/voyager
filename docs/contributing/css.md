# CSS

## Mixins

We use a variety of mixins for all kind of colors (border, text, background ...)  
Those mixins easily generate a CSS variable which can be overriden by theme plugins.

### Background color

```
@import "@sassmixins/bg-color";

.myclass {
    @include bg-color(my-class-background, 'colors.gray.500');
}
```

### Text color

```
@import "@sassmixins/text-color";

.myclass {
    @include text-color(my-class-text-color, 'colors.gray.500');
}
```

### Border color

```
@import "@sassmixins/border-color";

.myclass {
    @include border-color(my-class-border, 'colors.gray.500');
}
```

### Note

As a rule of thumb: you should never directly include a color, for example `color: red;`.  
Instead use the appropriate mixin, give it a reasonable name and provide a default value.  
For example:  
```
@import "sassmixins/bg-color";
@import "sassmixins/text-color";

.body {
    @include bg-color(bg-color, 'colors.gray.500');
    @include text-color(text-color, 'colors.red.500');
}
```

This will allow theme-developers to override your colors.