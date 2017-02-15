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
        factory(\Core\DAL\Models\Photo::class, 100)->create()->each(function (\Core\DAL\Models\Photo $photo) {
            $photo->thumbnails()->save(factory(\Core\DAL\Models\Thumbnail::class)->make());
            $photo->thumbnails()->save(factory(\Core\DAL\Models\Thumbnail::class)->make());
            $photo->tags()->save(factory(\Core\DAL\Models\Tag::class)->make());
            $photo->tags()->save(factory(\Core\DAL\Models\Tag::class)->make());
            $photo->tags()->save(factory(\Core\DAL\Models\Tag::class)->make());
        });
    }
}
