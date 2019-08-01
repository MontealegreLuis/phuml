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
	@vendor/bin/php-cs-fixer fix --config=.php_cs -v --using-cache false
	@vendor/bin/php-cs-fixer fix --config=.php_cs_tests -v --using-cache false

check:
	@docker-compose run --rm tests vendor/bin/grumphp run
	@vendor/bin/php-cs-fixer fix --config=.php_cs_tests -v --dry-run --using-cache false
