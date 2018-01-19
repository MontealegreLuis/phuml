# Contributing to phUML

## Workflow

* Fork the project.
* Make your bug fix or feature addition.
* Add tests for it. This is important so we don't break it in a future version unintentionally.
* Send a pull request. Bonus points for topic branches.

Pull requests for bug fixes must be based on the current stable branch whereas pull requests for new features must be based on the `master` branch.

## Coding Guidelines

This project follows the coding standards proposed in [PSR-2][1]

You can use [PHP CS fixer][2] to (re)format your sourcecode for compliance with this project's coding guidelines:

```bash
$ composer global require friendsofphp/php-cs-fixer

$ php-cs-fixer fix src
```

## Using phUML from a Git checkout

The following commands can be used to perform the initial checkout of phUML:

```bash
$ git clone git://github.com/MontealegreLuis/phuml.git

$ cd phuml
```

Retrieve phUML's dependencies using [Composer](https://getcomposer.org/):

```bash
$ composer install
```

## Running phUML's test suite

Run the test suite using PHPUnit:

$ vendor/bin/phpunit --testdox

## Reporting issues

Before opening a new ticket, please search for [existing issues][3].

[1]: http://www.php-fig.org/psr/psr-2/
[2]: https://github.com/FriendsOfPHP/PHP-CS-Fixer
[3]: https://github.com/MontealegreLuis/phuml/issues
