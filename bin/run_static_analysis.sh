#!/bin/bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
mkdir -p $DIR/../build
cd $DIR/..
$DIR/phpcs --standard=phpcs.xml $DIR/../src/ --extensions=php --report=checkstyle --report-file=$DIR/../build/phpcs.xml --ignore=Tests
$DIR/phpcpd --names="*.php" $DIR/../src/ --log-pmd=$DIR/../build/pmd-cpd.xml --exclude=Tests
$DIR/phpmd $DIR/../src/ xml codesize,unusedcode --reportfile $DIR/../build/pmd.xml --exclude Tests
