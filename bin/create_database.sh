#!/usr/bin/env bash

env=dev

./app/console doctrine:database:drop --env=$env --force $2
./app/console doctrine:database:create --env=$env $2
./app/console doctrine:schema:create --env=$env $2
./app/console doctrine:fixtures:load --env=$env $2 -n
