build:
	docker-compose up --build -d
test:
	vendor/bin/phpunit
lint:
	./vendor/bin/phpcs -- --standard=PSR12 app/Services app/Jobs tests
lint-fix:
	./vendor/bin/phpcbf -- --standard=PSR12 app/Services app/Jobs tests
migration:
	php artisan migrate --force
generate-key:
	composer run post-autoload-dump
	composer run post-root-package-install
	composer run post-create-project-cmd
	php artisan config:cache
