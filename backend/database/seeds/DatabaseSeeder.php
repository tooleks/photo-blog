<?php

use Illuminate\Database\Seeder;

/**
 * Class DatabaseSeeder
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Photo::class, 100)->create()->each(function (\App\Models\Photo $photo) {
            $photo->thumbnails()->save(factory(\App\Models\Thumbnail::class)->make());
            $photo->thumbnails()->save(factory(\App\Models\Thumbnail::class)->make());
            $photo->tags()->save(factory(\App\Models\Tag::class)->make());
            $photo->tags()->save(factory(\App\Models\Tag::class)->make());
            $photo->tags()->save(factory(\App\Models\Tag::class)->make());
        });
    }
}
