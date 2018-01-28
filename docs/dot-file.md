# Generate a DOT file

The `phuml:dot` command will generate a [DOT][1] file by scanning the code from a given directory.

## Why would you want a DOT file?

Imagine you don't want to produce a `png` class diagram but a searchable `pdf` file.
You could run the `phuml:dot` command to generate a `gv` file.
Then you might call `neato` to produce your `pdf`

```
vendor/bin/phuml phuml:dot tests/resources/.code output.gv
neato -Tpdf output.gv > output.pdf
rm -f output.gv
```

## Arguments and options

The `phuml:dot` command has the following arguments:

* `directory`. The directory with the code to be scanned to generate the DOT file
* `output`. The file name for your `gv` file

It has the following options:

* `associations` (`-a`). If present the command will generate association among classes.
  It will extract them from the types of the attributes of the class/interface.
  It will also use the types from the arguments passed to the constructor
* `recursive` (`-r`). If present it will scan the given `directory` recursively
* `hide-private` (`-i`). If present it will exclude private methods and attributes
* `hide-protected` (`-o`). If present it will exclude protected methods and attributes

## Examples

* The following command will produce a DOT file from the `tests/resources/.code` directory.
* The DOT file will be saved to the file `example.gv` in the current directory.
* It will search for classes and interfaces recursively, because of the `-r` option.
* It will generate associations (`-a`), by inspecting attributes and constructor parameters in all the classes.
* It will only show public methods and attributes because both options `-i` and `-o` are present

```
$ vendor/bin/phuml phuml:dot -r -a -i -o tests/resources/.code example.gv
```

* The following command will produce a DOT file from the `tests/resources/.code` directory.
* The DOT file will be saved to the file `example.gv` in the current directory.
* It **WILL NOT** search for classes and interfaces recursively, because of the lack of the `-r` option.
* It **WILL NOT** generate associations because of the lack of the `-a` option.
* It will show all methods and attributes because both options `-i` and `-o` are absent

```
$ vendor/bin/phuml phuml:dot tests/resources/.code example.gv
```

[1]: https://en.wikipedia.org/wiki/DOT_(graph_description_language)
