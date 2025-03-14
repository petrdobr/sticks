up:
	docker-compose up -d

down:
	docker-compose down

bash:
	docker-compose exec php-fpm sh

logs:
	docker-compose logs -f

install:
	docker-compose exec php-fpm composer create-project symfony/skeleton .

test:
	docker exec -it sticks_php-fpm_1 php vendor/bin/phpunit

# Set up the test database (run once or when needed)
test-setup:
	docker exec -it sticks_php-fpm_1 php bin/console doctrine:database:create --env=test
	docker exec -it sticks_php-fpm_1 php bin/console doctrine:migrations:migrate --env=test
# docker exec -it sticks_php-fpm_1 php bin/console doctrine:fixtures:load --env=test

# Reset the test database before running tests
test-reset:
	docker exec -it sticks_php-fpm_1 php bin/console doctrine:database:drop --force --env=test
	docker exec -it sticks_php-fpm_1 php bin/console doctrine:database:create --env=test
	docker exec -it sticks_php-fpm_1 php bin/console doctrine:migrations:migrate --env=test
#docker exec -it sticks_php-fpm_1 php bin/console doctrine:fixtures:load --env=test

# Run PHPUnit with coverage report
test-coverage:
	docker exec -it sticks_php-fpm_1 php bin/phpunit --coverage-html var/coverage
	@echo "Open file://$(shell pwd)/var/coverage/index.html in your browser to view the report"