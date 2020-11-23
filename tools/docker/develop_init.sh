#!/bin/bash

set -e
set -x

PROJECT_ROOT="$(dirname "$(dirname "$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)")")"
NFQ_ACTION_NAME="$2"

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
setPerms "${PROJECT_ROOT}/tests/Application/public"
setPerms "${PROJECT_ROOT}/tests/Application/public/media"

time composer --no-interaction install

cd "${PROJECT_ROOT}/tests/Application"

if [ ! -e "/home/project/.installed" ]; then
	bin/console doctrine:database:drop --force || true
	bin/console doctrine:database:drop --env=test --force || true
  touch "/home/project/.installed"
fi

(
	bin/console doctrine:database:create --if-not-exists -n
	bin/console doctrine:migrations:migrate -n
) &
(
	bin/console doctrine:database:create --env=test --if-not-exists -n
	bin/console doctrine:migrations:migrate --env=test -n
) &

wait

bin/console assets:install
bin/console sylius:fixtures:load -n

yarn install
yarn build
