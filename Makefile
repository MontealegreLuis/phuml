SHELL = /bin/bash

ARGS=""

.PHONY: help
help: ## Show help
	@echo Please specify a build target. The choices are:
	@grep -E '^[0-9a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

.PHONY: test
test: ## Run all the tests using a Docker container
	@docker-compose run --rm tests php vendor/bin/phpunit --testdox

.PHONY: coverage
coverage: ## Generate the code coverage report using a Docker container
	@docker-compose run --rm -e XDEBUG_MODE=coverage tests php vendor/bin/phpunit --coverage-html build/coverage

.PHONY: infection
infection: ## Execute the mutation testing suite using a Docker container
	@docker-compose run --rm -e XDEBUG_MODE=coverage tests php vendor/bin/infection --threads=4

.PHONY: format
format: ## Fix Coding Standard violations in production and test code
	@vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.php -v --using-cache no
	@vendor/bin/php-cs-fixer fix --config=.php-cs-fixer-tests.php -v --using-cache no

.PHONY: refactor
refactor: ## Apply automated refactorings using Rector
	@vendor/bin/rector process

.PHONY: check
check: ## Execute all code quality checks
	@vendor/bin/grumphp run --no-interaction
	@vendor/bin/composer-require-checker check
	@docker-compose run --rm tests vendor/bin/phpunit --testsuite 'Integration tests'
	@vendor/bin/rector process --dry-run

.PHONY: diagram
diagram: ## Generate a class diagram with phUML using a Docker container
	@docker-compose run --rm tests php bin/phuml phuml:diagram $(ARGS)

.PHONY: dot
dot: ## Generate a DOT file with phUML using a Docker container
	@docker-compose run --rm tests php bin/phuml phuml:dot $(ARGS)

.PHONY: stats
stats: ## Generate a statistics file with phUML using a Docker container
	@docker-compose run --rm tests php bin/phuml phuml:statistics $(ARGS)
