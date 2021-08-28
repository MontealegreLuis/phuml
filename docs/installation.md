---
currentMenu: installation
---

# Installation

## Docker

The official phUML Docker image can be found on [Docker Hub](https://hub.docker.com/r/montealegreluis/phuml/).

```bash
docker pull montealegreluis/phuml:5.2.0
```

You can replace `5.2.0` with any of th available [tags](https://hub.docker.com/r/montealegreluis/phuml/tags?page=1&ordering=last_updated)

## Composer

Alternatively, you may use  [Composer](https://getcomposer.org/) to download and install phUML as well as its dependencies.

```bash
composer require phuml/phuml
```

phUML can also be installed globally.

```bash
composer global require phuml/phuml
```
