SHELL = /bin/bash

.PHONY: test fix diagram dot stats

ARGS=""

test:
	@docker-compose run --rm tests php vendor/bin/phpunit --coverage-text --coverage-clover=coverage.clover

diagram:
	@docker-compose run --rm tests php bin/phuml phuml:diagram $(ARGS)

dot:
	@docker-compose run --rm tests php bin/phuml phuml:dot $(ARGS)

stats:
	@docker-compose run --rm tests php bin/phuml phuml:statistics $(ARGS)

fix:
	@php-cs-fixer fix src --rules=@PSR2,no_unused_imports
	@php-cs-fixer fix tests --rules=no_unused_imports
