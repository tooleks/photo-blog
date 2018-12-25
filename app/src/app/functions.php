<?php

namespace App;

/**
 * Determine whether the application is running in production environment.
 *
 * @return bool
 */
function env_production(): bool
{
    return env('APP_ENV') === 'production';
}
