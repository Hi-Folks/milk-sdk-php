phpunit:
	vendor/bin/phpunit --coverage-text

phpstan:
	vendor/bin/phpstan analyse -c phpstan.neon

phpcs:
	phpcs --standard=PSR12 src
