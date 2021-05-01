.PHONY: help phpstan test coverage phpcs allcheck

.DEFAULT_GOAL := help

help:	## Show this help (default).
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

coverage: ## Execute phpunit showing coverage
	vendor/bin/phpunit --coverage-text

test: ## Execute phpunit
	vendor/bin/phpunit --testdox

phpstan: ## Execute phpstan from phpstan.neon
	vendor/bin/phpstan analyse -c phpstan.neon

phpcs: ## Execute PhpCS with PSR12 standard on src directory
	vendor/bin/phpcs --standard=PSR12 src tests

fixpsr12: ## Fix style for PSR12 standard on src directory
	vendor/bin/phpcbf --standard=PSR12 src tests

allcheck: phpcs phpstan test ## Perform all check: phpcs, phpstan and test
