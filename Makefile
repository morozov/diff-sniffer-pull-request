install:
	composer install
test:
	vendor/bin/phpunit --color
	vendor/bin/phpstan analyse -l 7 src tests -c phpstan.neon
	vendor/bin/phpcs
coverage:
	$(eval TMPDIR=$(shell mktemp -d))
	vendor/bin/phpunit --coverage-html=$(TMPDIR)
	xdg-open $(TMPDIR)/index.html
