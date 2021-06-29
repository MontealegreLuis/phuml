SHELL = /bin/bash

.PHONY: test coverage format check diagram dot stats

ARGS=""

test:
	@docker-compose run --rm tests php vendor/bin/phpunit --testdox

coverage:
	@docker-compose run --rm tests php vendor/bin/phpunit --coverage-html build/coverage


diagram:
	@docker-compose run --rm tests php bin/phuml phuml:diagram $(ARGS)

dot:
	@docker-compose run --rm tests php bin/phuml phuml:dot $(ARGS)

stats:
	@docker-compose run --rm tests php bin/phuml phuml:statistics $(ARGS)

format:
	@vendor/bin/rector process
	@vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.php -v --using-cache no
	@vendor/bin/php-cs-fixer fix --config=.php-cs-fixer-tests.php -v --using-cache no

check:
	@vendor/bin/grumphp run
	@vendor/bin/composer-require-checker check
	@docker-compose run --rm tests vendor/bin/phpunit --testsuite 'Integration tests'
	@vendor/bin/rector process --dry-run
