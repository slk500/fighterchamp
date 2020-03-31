COMPOSE_EXEC_PHP = docker-compose exec php
COMPOSE_EXEC_CONSOLE = ${COMPOSE_EXEC_PHP} bin/console

up:
	docker-compose up -d $(a)

docker-build:
	docker-compose build

composer-install:
	${COMPOSE_EXEC_PHP} composer install

composer-update:
	${COMPOSE_EXEC_PHP} composer update

db-migrate:
	${COMPOSE_EXEC_CONSOLE} doctrine:migrations:migrate

db-create:
	${COMPOSE_EXEC_CONSOLE} doctrine:database:create

db-update-schema:
	${COMPOSE_EXEC_CONSOLE} doctrine:schema:update --force

db-drop:
	${COMPOSE_EXEC_CONSOLE} doctrine:database:drop --force

db-load-fixtures:
	${COMPOSE_EXEC_CONSOLE} doctrine:fixtures:load

test:
	${COMPOSE_EXEC_PHP} vendor/bin/phpunit
	${COMPOSE_EXEC_PHP} vendor/bin/behat

unit:
	${COMPOSE_EXEC_PHP} vendor/bin/phpunit

unit-fail-fast:
	${COMPOSE_EXEC_PHP} vendor/bin/phpunit --stop-on-failure

behat:
	${COMPOSE_EXEC_PHP} vendor/bin/behat

fixer:
	${COMPOSE_EXEC_PHP} vendor/bin/php-cs-fixer fix --diff --dry-run --verbose

setup-keys:
	mkdir -p var/jwt
	openssl genpkey -out var/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
	openssl pkey -in var/jwt/private.pem -out var/jwt/public.pem -pubout

setup-project:
	docker-compose build
	docker-compose up -d
	docker-compose exec php composer install
	docker-compose exec php php bin/console cache:clear --env=dev
	docker-compose exec php php bin/console cache:clear --env=test
	docker-compose exec php php bin/console do:da:c --if-not-exists --env=dev
	docker-compose exec php php bin/console do:da:c --if-not-exists --env=test
	docker-compose exec php php bin/console do:sch:up --force
	docker-compose exec php php bin/console do:mi:mi --no-interaction --allow-no-migration --env=dev
	docker-compose exec php php bin/console do:mi:mi --no-interaction --allow-no-migration --env=test
	docker-compose exec php bin/console assets:install --symlink web

