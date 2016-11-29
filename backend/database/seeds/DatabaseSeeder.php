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
        factory(\App\Models\DB\Photo::class, 100)->create()->each(function (\App\Models\DB\Photo $photo) {
            $photo->thumbnails()->save(factory(\App\Models\DB\Thumbnail::class)->make());
            $photo->thumbnails()->save(factory(\App\Models\DB\Thumbnail::class)->make());
            $photo->tags()->save(factory(\App\Models\DB\Tag::class)->make());
            $photo->tags()->save(factory(\App\Models\DB\Tag::class)->make());
            $photo->tags()->save(factory(\App\Models\DB\Tag::class)->make());
        });
    }
}
