#!/bin/bash

set -e

# Codesniffing
php vendor/bin/phpcs --standard=PSR2 app/Judge

# PHPUnit Tests
php vendor/bin/phpunit
