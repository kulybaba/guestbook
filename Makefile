SHELL := /bin/bash

tests:
	APP_ENV=test symfony console doctrine:fixtures:load -n
	symfony run bin/phpunit
.PHONY: tests
