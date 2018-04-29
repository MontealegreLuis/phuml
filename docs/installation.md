# Installation

## PHAR

We distribute a PHP Archive [(PHAR)][phar] that has all required dependencies of phUML bundled in a single file.
The PHAR file is signed with an openssl private key.
You will need the pubkey file to be stored beside the PHAR file at all times in order to use it.
If you rename `phuml.phar` to `phuml`, for example, then also rename the key from `phuml.phar.pubkey` to `phuml.pubkey`.

To install `phuml` globally run the following commands in your terminal.

```bash
$ wget https://montealegreluis.com/phuml/phuml.phar
$ wget https://montealegreluis.com/phuml/phuml.phar.pubkey
$ chmod +x phuml.phar
$ mv phuml.phar /usr/local/bin/phuml
$ mv phuml.phar.pubkey /usr/local/bin/phuml.pubkey
```

Run the PHAR to see all the available commands and options

```bash
$ phuml
```

You can also immediately use the PHAR after you have downloaded it, of course:

```bash
$ wget https://montealegreluis.com/phuml/phuml.phar
$ wget https://montealegreluis.com/phuml/phuml.phar.pubkey
$ php phuml.phar
```

### PHAR Updates

To update your current PHAR run:

```bash
$ phuml self-update
```

## Composer

Alternatively, you may use  [Composer][composer] to download and install phUML as well as its dependencies.

```bash
$ composer require phuml/phuml
```

[composer]: https://getcomposer.org/
[phar]: https://php.net/phar
