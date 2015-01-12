#!/bin/bash

set -e

function sniff() {
    # Codesniffing
    php vendor/bin/phpcs --standard=PSR2 app/Judge
}

function unit() {
    # PHPUnit Tests
    php vendor/bin/phpunit
}

# if a parameter is provided, treat it as the build "targets to run". Useful for local dev
if [ $1 ]; then
    $1
    exit
fi

# otherwise, do the default
sniff
unit
