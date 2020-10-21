.PHONY: help phpstan test coverage phpcs allcheck

help:           ## Show this help.
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' | sed -e 's/##//'

phpunit:
	vendor/bin/phpunit --coverage-text

phpstan:
	vendor/bin/phpstan analyse -c phpstan.neon

phpcs:
	vendor/bin/phpcs --standard=PSR12 src
