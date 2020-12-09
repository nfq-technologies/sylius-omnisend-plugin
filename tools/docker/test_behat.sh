#!/bin/bash

set -xe

PROJECT_ROOT="$(dirname $(dirname $(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)))"

echo "PROJECT ROOT: ${PROJECT_ROOT}"
cd "${PROJECT_ROOT}"

function setPerms {
	mkdir -p "$1"
	sudo setfacl -R  -m m:rwx -m u:33:rwX -m u:1000:rwX "$1"
	sudo setfacl -dR -m m:rwx -m u:33:rwX -m u:1000:rwX "$1"
}

echo -e '\n## Setting up permissions ... '
setPerms "${PROJECT_ROOT}/tests/Application/var"
setPerms "${PROJECT_ROOT}/tests/Application/var/cache"
setPerms "${PROJECT_ROOT}/tests/Application/var/logs"
setPerms "${PROJECT_ROOT}/etc/build"

echo "## Executing Behat Tests"

vendor/bin/behat --no-colors --no-snippets --tags '~@skip'
