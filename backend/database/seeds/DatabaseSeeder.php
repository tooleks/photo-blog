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
        factory(\Core\Models\Photo::class, 100)->create()->each(function (\Core\Models\Photo $photo) {
            $photo->thumbnails()->save(factory(\Core\Models\Thumbnail::class)->make());
            $photo->thumbnails()->save(factory(\Core\Models\Thumbnail::class)->make());
            $photo->tags()->save(factory(\Core\Models\Tag::class)->make());
            $photo->tags()->save(factory(\Core\Models\Tag::class)->make());
            $photo->tags()->save(factory(\Core\Models\Tag::class)->make());
        });
    }
}
