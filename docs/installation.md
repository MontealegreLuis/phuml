---
currentMenu: installation
---

# Installation

## Phive

phUML can be installed by [Phive](https://phar.io/) - The PHAR Installation and Verification Environment.

```
phive install phuml
```

Phive will generate a `.phive` and a `tools` directory which you may want to add to your `.gitignore` file. 

## Docker

The official phUML Docker image can be found on [Docker Hub](https://hub.docker.com/r/montealegreluis/phuml/).

```bash
docker pull montealegreluis/phuml:5.2.0
```

You can replace `5.2.0` with any of th available [tags](https://hub.docker.com/r/montealegreluis/phuml/tags?page=1&ordering=last_updated)

## Composer

phUML can be installed globally by [Composer](https://getcomposer.org/).

```bash
composer global require phuml/phuml
```

Alternatively, you may want to install phUML as well as its dependencies.

```bash
composer require phuml/phuml
```
