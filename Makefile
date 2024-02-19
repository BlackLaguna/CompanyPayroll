init-test-db:
	bin/console --env=test doctrine:database:drop --force --if-exists
	bin/console --env=test doctrine:database:create
	bin/console --env=test doctrine:migration:migrate --no-interaction
	bin/console --env=test doctrine:fixtures:load --no-interaction

test:
	bin/console --env=test c:c
	bin/phpunit

run:
	composer install --no-interaction
	bin/console --env=dev doctrine:database:create --if-not-exists
	bin/console --env=dev doctrine:migration:migrate --no-interaction
	bin/console --env=dev doctrine:fixtures:load --no-interaction

up:
	docker compose up -d

load-dev-fixtures:
	bin/console --env=dev doctrine:fixtures:load --no-interaction

analyze:
	vendor/bin/phpstan analyse src --level 9
	vendor/bin/ecs