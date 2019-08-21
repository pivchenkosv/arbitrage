build:
	docker-compose up --build -d
test:
	vendor/bin/phpunit
lint:
	./vendor/bin/phpcs -- --standard=PSR12 app tests
lint-fix:
	./vendor/bin/phpcbf -- --standard=PSR12 app tests
migration:
	php artisan migrate
generate-key:
	composer run post-autoload-dump
	composer run post-root-package-install
	composer run post-create-project-cmd
