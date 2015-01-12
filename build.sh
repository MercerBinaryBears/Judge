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

function acceptance() {
    # start the server, and save the pid
    php artisan serve &
    sleep 1
    ps -o pid,command | grep -v grep | grep server.php | cut -d' ' -f1 > .artisan_id.txt

    # run tests
    vendor/bin/behat

    # kill the server
    kill `cat .artisan_id.txt`
    rm .artisan_id.txt
}

# if a parameter is provided, treat it as the build "targets to run". Useful for local dev
if [ $1 ]; then
    $1
    exit
fi

# otherwise, do the default
sniff
unit
