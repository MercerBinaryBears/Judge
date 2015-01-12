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

sniff
unit
