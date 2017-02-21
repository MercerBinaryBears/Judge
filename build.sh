#!/bin/bash

set -e

composer install

# Codesniffing
php vendor/bin/phpcs --standard=PSR2 app/Judge

# PHPUnit Tests
php vendor/bin/phpunit -c phpunit.log.xml
