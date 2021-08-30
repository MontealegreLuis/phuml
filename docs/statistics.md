---
currentMenu: statistics
---

# Generate statistics

The `phuml:statistics` command will generate a text file with statistics about the code from a given directory.

The `phuml:statistics` command has the following arguments:

* `directory`. The directory with the code to be scanned to generate the statistics file
* `output`. The file name for your `txt` file with the statistics

It has the following options:

* `recursive` (`-r`). If present it will scan the given `directory` recursively

## Examples

* The following command will produce a statistics file from the `src` directory.
* The DOT file will be saved to the file `example.txt` in the current directory.
* It will search for classes and interfaces recursively, because of the `-r` option.

```
# Composer installation
vendor/bin/phuml phuml:statistics -r src example.txt
# Docker installation
docker run --rm -v $PWD:/code montealegreluis/phuml phuml:statistics -r src example.txt
# Phive installation
tools/phuml phuml:statistics -r src example.txt
```

* The following command will produce a statistics file from the `src` directory.
* The statistics file will be saved to the file `example.txt` in the current directory.
* It **WILL NOT** search for classes and interfaces recursively, because of the lack of the `-r` option.

```
# Composer installation
vendor/bin/phuml phuml:statistics src example.txt
# Docker installation
docker run --rm -v $PWD:/code montealegreluis/phuml phuml:statistics src example.txt
# Phive installation
tools/phuml phuml:statistics src example.txt
```
