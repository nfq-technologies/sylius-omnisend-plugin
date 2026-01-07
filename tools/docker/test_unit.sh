#!/bin/bash

PROJECT_ROOT="$(dirname $(dirname $(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)))"

echo "PROJECT ROOT: ${PROJECT_ROOT}"
cd "${PROJECT_ROOT}"

echo "## Executing Unit Tests"
rm -rf "$PROJECT_ROOT/tests/Application/var/cache/"
./bin/phpunit --log-junit test_unit.xml

code=$?

if [ "$code" != "0" ]; then
	echo "CRITICAL - tests FAILED"
	exit 1
fi

echo "## Executing phpstan"

./bin/phpstan analyse -c phpstan.neon -l max src/

code=$?

if [ "$code" != "0" ]; then
	echo "CRITICAL - phpstan FAILED"
	exit 1
fi

echo "OK - all test passed"
exit 0
