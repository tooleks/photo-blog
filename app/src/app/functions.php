<?php

namespace App;

/**
 * @return bool
 */
function env_production(): bool
{
    return env('APP_ENV') === 'production';
}
