#!/bin/bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
mkdir -p $DIR/../build
mkdir -p $DIR/../build/tests
mkdir -p $DIR/../build/tests/coverage
cd $DIR/..
composer install
php -d zend_extension=xdebug.so -d xdebug.profiler_enable=on $DIR/phpunit --log-junit "$DIR/../build/tests/phpunit.xml" --coverage-clover "$DIR/../build/clover.xml" --coverage-crap4j '/vagrant/build/crap4j.xml'  --coverage-html "$DIR/../build/tests/coverage/"
