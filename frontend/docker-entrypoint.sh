#!/bin/bash

set -e

if [ "$REBUILD_ON_STARTUP" == "1" ]; then
    yarn install
    npm run build
fi

exec "$@"
