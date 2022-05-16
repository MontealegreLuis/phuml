---
currentMenu: format
---

# Diagram Format

## Interfaces and classes

* Names are shown in **bold**
* Static methods and functions are shown underlined
* Abstract classes, abstract methods and interfaces names are shown in *italics*

## Traits, Attributes, and Enums

[Traits](https://www.php.net/manual/en/language.oop5.traits.php), class [attributes](https://www.php.net/manual/en/language.attributes.overview.php)(annotations), and [enumerations](https://www.php.net/manual/en/language.enumerations.php) will be shown with a [UML stereotype](https://www.uml-diagrams.org/stereotype.html) above its name.
Traits will be shown with the `<<trait>>` stereotype, attributes (annotations) will be shown with the `<<attribute>>` stereotype, and enumerations will be shown with the `<<enum>>` stereotype. 

## Enumerations

Case definitions in enumerations will be shown without a visibility modifier (`+`, `-`, `#`) and to differentiate them from constants, cases won't be in italics.

If an enum defines constants, they will be shown above its cases.

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
