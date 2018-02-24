SHELL = /bin/bash

.PHONY: test diagram dot stats

ARGS=""

test:
	@docker-compose run --rm tests php vendor/bin/phpunit --coverage-text --coverage-clover=coverage.clover

diagram:
	@docker-compose run --rm tests php bin/phuml phuml:diagram $(ARGS)

dot:
	@docker-compose run --rm tests php bin/phuml phuml:dot $(ARGS)

stats:
	@docker-compose run --rm tests php bin/phuml phuml:statistics $(ARGS)
