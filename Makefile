.PHONY: help phpstan test coverage phpcs allcheck

phpunit:
	vendor/bin/phpunit --coverage-text

phpstan:
	vendor/bin/phpstan analyse -c phpstan.neon

phpcs:
	phpcs --standard=PSR12 src

help:   ## Show this help.
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' | sed -e 's/##//'
