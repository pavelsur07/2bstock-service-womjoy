up: docker-up
down: docker-down
restart: docker-down docker-up

init: docker-down-clear site-clear docker-up site-init
#docker-pull docker-build docker-up manager-init

#test: manager-test
#test-coverage: manager-test-coverage
#test-unit: manager-test-unit
#test-unit-coverage: manager-test-unit-coverage

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-down-clear:
	docker-compose down -v --remove-orphans

docker-pull:
	docker-compose pull

docker-build:
	docker-compose build --pull

site-init: site-composer-install site-wait-db site-migrations site-fixtures
# site-assets-install
# manager-fixtures manager-ready

site-clear:
	docker run --rm -v ${PWD}/site:/app --workdir=/app alpine rm -f .ready

site-composer-install:
	docker-compose run --rm cli composer install

site-assets-install:
	docker-compose run --rm site-node yarn install
	docker-compose run --rm site-node yarn build
	#docker-compose run --rm site-node npm rebuild node-sass

site-wait-db:
	until docker-compose exec -T site-postgres pg_isready --timeout=0 --dbname=app ; do sleep 1 ; done

site-migrations:
	docker-compose run --rm cli php bin/console doctrine:migrations:migrate --no-interaction

site-fixtures:
	docker-compose run --rm cli php bin/console doctrine:fixtures:load --no-interaction

site-ready:
	docker run --rm -v ${PWD}/manager:/app --workdir=/app alpine touch .ready

site-assets-dev:
	docker compose run --rm site-node npm run dev

site-test:
	docker compose run --rm cli php bin/phpunit

site-test-coverage:
	docker-compose run --rm cli php bin/phpunit --coverage-clover var/clover.xml --coverage-html var/coverage

site-test-unit:
	docker compose run --rm cli php bin/phpunit --testsuite=unit

site-test-unit-coverage:
	docker-compose run --rm cli php bin/phpunit --testsuite=unit --coverage-clover var/clover.xml --coverage-html var/coverage

build-production:
	docker build --pull --file=manager/docker/production/nginx.docker --tag ${REGISTRY_ADDRESS}/manager-nginx:${IMAGE_TAG} manager
	docker build --pull --file=manager/docker/production/php-fpm.docker --tag ${REGISTRY_ADDRESS}/manager-php-fpm:${IMAGE_TAG} manager
	docker build --pull --file=manager/docker/production/php-cli.docker --tag ${REGISTRY_ADDRESS}/manager-php-cli:${IMAGE_TAG} manager
	docker build --pull --file=manager/docker/production/postgres.docker --tag ${REGISTRY_ADDRESS}/manager-postgres:${IMAGE_TAG} manager
	docker build --pull --file=manager/docker/production/redis.docker --tag ${REGISTRY_ADDRESS}/manager-redis:${IMAGE_TAG} manager
	docker build --pull --file=centrifugo/docker/production/centrifugo.docker --tag ${REGISTRY_ADDRESS}/centrifugo:${IMAGE_TAG} centrifugo

push-production:
	docker push ${REGISTRY_ADDRESS}/manager-nginx:${IMAGE_TAG}
	docker push ${REGISTRY_ADDRESS}/manager-php-fpm:${IMAGE_TAG}
	docker push ${REGISTRY_ADDRESS}/manager-php-cli:${IMAGE_TAG}
	docker push ${REGISTRY_ADDRESS}/manager-postgres:${IMAGE_TAG}
	docker push ${REGISTRY_ADDRESS}/manager-redis:${IMAGE_TAG}
	docker push ${REGISTRY_ADDRESS}/centrifugo:${IMAGE_TAG}

deploy-production:
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'rm -rf docker-compose.yml .env'
	scp -o StrictHostKeyChecking=no -P ${PRODUCTION_PORT} docker-compose-production.yml ${PRODUCTION_HOST}:docker-compose.yml
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'echo "REGISTRY_ADDRESS=${REGISTRY_ADDRESS}" >> .env'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'echo "IMAGE_TAG=${IMAGE_TAG}" >> .env'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'echo "MANAGER_APP_SECRET=${MANAGER_APP_SECRET}" >> .env'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'echo "MANAGER_DB_PASSWORD=${MANAGER_DB_PASSWORD}" >> .env'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'echo "MANAGER_REDIS_PASSWORD=${MANAGER_REDIS_PASSWORD}" >> .env'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'echo "MANAGER_MAILER_URL=${MANAGER_MAILER_URL}" >> .env'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'echo "MANAGER_OAUTH_FACEBOOK_SECRET=${MANAGER_OAUTH_FACEBOOK_SECRET}" >> .env'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'echo "STORAGE_BASE_URL=${STORAGE_BASE_URL}" >> .env'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'echo "STORAGE_FTP_HOST=${STORAGE_FTP_HOST}" >> .env'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'echo "STORAGE_FTP_USERNAME=${STORAGE_FTP_USERNAME}" >> .env'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'echo "STORAGE_FTP_PASSWORD=${STORAGE_FTP_PASSWORD}" >> .env'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'echo "CENTRIFUGO_WS_HOST=${CENTRIFUGO_WS_HOST}" >> .env'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'echo "CENTRIFUGO_API_KEY=${CENTRIFUGO_API_KEY}" >> .env'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'echo "CENTRIFUGO_SECRET=${CENTRIFUGO_SECRET}" >> .env'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'docker compose pull'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'docker compose up --build -d'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'until docker compose exec -T manager-postgres pg_isready --timeout=0 --dbname=app ; do sleep 1 ; done'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'docker compose run --rm manager-php-cli php bin/console doctrine:migrations:migrate --no-interaction'
