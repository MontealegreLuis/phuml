# Format

## Interfaces and classes

* Names are shown in **bold**
* Static methods and functions are shown underlined
* Abstract classes, abstract methods and interfaces names are shown in *italics*

## Relationships

* **Associations** are solid lines without arrows
* **Inheritance** is a solid line with an empty arrow pointing to the parent
* Interface **implementations** are dashed lines with an empty arrow pointing to the interface
* **Trait composition** is a solid line with an arrow pointing to the trait being used

## Possible output differences

You might get an output different from the screenshots in this documentation.
Some Graphviz versions do not support some HTML tags like `<i>`, `<b>`, etc.

For more information about the support for different HTML tags in Graphviz, read [here][1].

## Running the commands from the container

If you want to get the same output as in the screenshots, use the Docker container in this package.

Use the following `make` commands to create the files using the Docker container

```
$ make diagram ARGS="src example.png -p neato"
$ make stats ARGS="src example.gv"
$ make stats ARGS="src statistics.txt"
```

With these commands you can pass the options and arguments via the `ARGS` variable

## Themes

This package offers 3 different color schemes (themes)

### phUML (default)

![phUML theme][2]

### PHP

![PHP theme][3]

### Classic

![Classic theme][4]

[1]: https://www.graphviz.org/doc/info/shapes.html#html
[2]: phuml-theme.png
[3]: php-theme.png
[4]: classic-theme.png
