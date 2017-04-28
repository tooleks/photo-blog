<?php

use Core\Models\Exif;
use Core\Models\Photo;
use Core\Models\Subscription;
use Core\Models\Tag;
use Core\Models\Thumbnail;
use Core\Models\User;
use Faker\Generator;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(Exif::class, function (Generator $faker) {
    return [
        'photo_id' => Photo::inRandomOrder()->first()->id,
        'data' => [],
    ];
});

$factory->define(Photo::class, function (Generator $faker) {
    return [
        'created_by_user_id' => User::inRandomOrder()->first()->id,
        'description' => $faker->realText(),
        'path' => sprintf('/%s/%s.%s', str_random(12), str_random(5), str_random(3)),
        'relative_url' => sprintf('/%s/%s/%s.%s', str_random(12), str_random(12), str_random(5), str_random(3)),
        'is_published' => $faker->boolean(75),
    ];
});

$factory->define(Subscription::class, function (Generator $faker) {
    return [
        'email' => $faker->safeEmail,
        'token' => str_random(64),
    ];
});

$factory->define(Tag::class, function (Generator $faker) {
    return [
        'text' => strtolower($faker->word),
    ];
});

$factory->define(Thumbnail::class, function (Generator $faker) {
    return [
        'path' => sprintf('/%s/%s.%s', str_random(12), str_random(5), str_random(3)),
        'relative_url' => sprintf('/%s/%s/%s.%s', str_random(12), str_random(12), str_random(5), str_random(3)),
        'width' => $faker->randomNumber(4),
        'height' => $faker->randomNumber(3),
    ];
});

$factory->define(User::class, function (Generator $faker) {
    static $password;
    return [
        'name' => $faker->userName,
        'email' => $faker->safeEmail,
        'api_token' => str_random(64),
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});
