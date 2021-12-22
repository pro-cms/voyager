# Best practices

This page shows some of the best practices regarding plugin development.

## Don't load things in the constructor

Your plugin class is loaded when calling `addPlugin(...)` even when it's disabled.
To prevent long loading times and unnecessary memory usage, load data only when needed (in route definitions, for example).