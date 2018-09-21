#!/usr/bin/env bash

set -e

# Run the artisan schedule command in background every minute.
while [ true ]
    do
      php artisan schedule:run --verbose --no-interaction &
      sleep 60
    done
