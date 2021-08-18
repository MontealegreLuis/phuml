---
currentMenu: format
---

# Diagram Format

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

For more information about the support for different HTML tags in Graphviz, read [here][shapes].

## Themes

This package offers 3 different color schemes (themes)

### phUML (default)

![phUML theme][phuml-theme]

### PHP

![PHP theme][php-theme]

### Classic

![Classic theme][classic-theme]

[shapes]: https://www.graphviz.org/doc/info/shapes.html#html
[phuml-theme]: phuml-theme.png
[php-theme]: php-theme.png
[classic-theme]: classic-theme.png
