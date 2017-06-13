test: ## Runs unit tests
	php ./vendor/bin/phpunit tests

check-linting: ## Checks the linting for code
	./vendor/bin/phpcs --standard=ZEND --standard=PSR2 --ignore=*/vendor/* ./

fix-linting: ## Fixes the linting in code
	./vendor/bin/phpcbf --standard=ZEND --standard=PSR2 --ignore=*/vendor/* ./

analyze-code:
	./vendor/bin/phan -z -b --color