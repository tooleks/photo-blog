#!/bin/bash

set -e

if [ "$DOCKER_REBUILD" == "1" ]; then
    yarn install
    if [ "$DOCKER_ENV" == "production" ]; then
        npm run build:production
    else
        npm run build
    fi
fi

exec "$@"
