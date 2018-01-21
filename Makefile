SHELL = /bin/bash

.PHONY: test

test:
	@docker-compose run --rm tests php vendor/bin/phpunit --coverage-text --coverage-clover=coverage.clover
