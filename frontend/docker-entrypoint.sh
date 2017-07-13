#!/bin/bash

set -e

if [ "$DOCKER_REBUILD" == "1" ]; then
    yarn install
    npm run build
fi

exec "$@"
