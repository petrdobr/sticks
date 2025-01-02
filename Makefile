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