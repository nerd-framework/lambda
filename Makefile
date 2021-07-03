install:
	composer install --prefer-dist

update:
	composer update --prefer-dist

autoload:
	composer dump-autoload

test:
	composer exec phpunit -- --color tests

lint:
	composer exec 'phpcs --standard=PSR2 src tests'
