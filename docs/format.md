# Format

## Interfaces and classes

* Names are shown in **bold**
* Static methods and functions are shown underlined
* Abstract classes, abstract methods and interfaces names are shown in *italics*

## Relationships

* Associations are solid lines without arrows
* Inheritance is a solid line with an arrow pointing to the parent
* Interface implementations are dashed lines with an arrow pointing to the interface

## Possible output differences

You might get an output different from the screenshots in this documentation.
Some Graphviz versions do not support some HTML tags like `<i>`, `<b>`, etc.

If you want to get the same output you can use the Docker container in this package.

This is how you can run the  `phuml:diagram` command from the container

```
$ docker-compose run --rm tests php bin/phuml phuml:diagram src class-diagram.png
```

The command above will produce a class diagram out of your `src` directory.
It will save the diagram in the file `class-diagram.png` in your current directory.

For more information about the support for different HTML tags in Graphviz, read [here][1].

[1]: https://www.graphviz.org/doc/info/shapes.html#html
