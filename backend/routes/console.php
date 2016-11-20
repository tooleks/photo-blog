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
    \App\Models\DB\Role::truncate();
    \App\Models\DB\Role::create(['name' => \App\Models\DB\Role::NAME_ADMINISTRATOR]);
    \App\Models\DB\Role::create(['name' => \App\Models\DB\Role::NAME_CUSTOMER]);
    $this->comment('Roles were successfully created.');
});

Artisan::command('cleanup:photos', function () {
    $dirNames = [];
    \App\Models\DB\Photo::chunk(100, function ($photos) use (&$dirNames) {
        foreach ($photos as $photo) {
            $dirName = pathinfo($photo->path, PATHINFO_DIRNAME);
            if ($dirName) {
                array_push($dirNames, $dirName);
                $this->comment($dirName);
            }
        }
    });
});
