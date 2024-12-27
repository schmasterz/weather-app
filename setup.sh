#!/bin/sh
cp .env.dist .env
composer install
php bin/console doctrine:database:create --env=test
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console doctrine:migrations:migrate --no-interaction --env=test
npm install
php bin/console react:update-api-key
npm run build
