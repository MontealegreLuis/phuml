# Generate a class diagram

The `phuml:diagram` command will generate a class diagram by scanning the code from a given directory.

It has the following arguments:

* `directory`. The directory with the code to be scanned to generate the class diagram
* `output`. The file name for your `png` class diagram

It has the following options:

* `processor` (`-p`). It will let you choose between `neato` and `dot` to generate the class diagram
* `associations` (`-a`). If present the command will generate association among classes.
  It will extract them from the types of the attributes of the class.
  It will also use the types from the arguments passed to the constructor
* `recursive` (`-r`). If present it will scan the given `directory` recursively
* `hide-private` (`-i`). If present it will exclude private methods and attributes
* `hide-protected` (`-o`). If present it will exclude protected methods and attributes

## Examples

* The following command will produce a class diagram from the `tests/resources/.code` directory.
* The diagram will be saved to the file `example.png` in the current directory.
* It will search for classes and interfaces recursively, because of the `-r` option.
* It will generate associations (`-a`), by inspecting attributes and constructor parameters in all the classes.
* It will use the `dot` command to generate the diagram, because of the `-p dot` option.
* It will only show public methods and attributes because both options `-i` and `-o` are present

```
$ vendor/bin/phuml phuml:diagram -r -a -i -o -p dot tests/resources/.code example.png
```

* The following command will produce a class diagram from the `tests/resources/.code` directory.
* The diagram will be saved to the file `example.png` in the current directory.
* It **WILL NOT** search for classes and interfaces recursively, because of the lack of the `-r` option.
* It **WILL NOT** generate associations because of the lack of the `-a` option.
* It will use the `neato` command to generate the diagram, because of the `-p neato` option.
* It will show all methods and attributes because both options `-i` and `-o` are absent

```
$ vendor/bin/phuml phuml:diagram -p neato tests/resources/.code example.png
```
