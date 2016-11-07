<?php

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('config:app', function () {
    $this->call('storage:link');
    $this->call('migrate');
    $this->call('make:roles');
});

Artisan::command('make:roles', function () {
    \App\Models\Role::truncate();
    \App\Models\Role::create(['name' => \App\Models\Role::NAME_ADMINISTRATOR]);
    \App\Models\Role::create(['name' => \App\Models\Role::NAME_CUSTOMER]);
    $this->comment('Roles were successfully created.');
});
