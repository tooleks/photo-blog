<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class MigratePostsColumnsFromPhotosTableToPostsTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     * @throws Throwable
     */
    public function up()
    {
        DB::statement("
            INSERT INTO posts (id, created_by_user_id, description, published_at, created_at, updated_at) 
            SELECT id, created_by_user_id, description, published_at, created_at, updated_at FROM photos
            WHERE published_at IS NOT NULL
        ");

        DB::statement("
            INSERT INTO posts_photos (post_id, photo_id)
            SELECT id AS post_id, id AS photo_id FROM posts 
        ");

        DB::statement("
            INSERT INTO posts_tags (post_id, tag_id)
            SELECT photo_id AS post_id, tag_id FROM photos_tags
            LEFT JOIN photos ON photos.id = photos_tags.photo_id
            WHERE photos.published_at IS NOT NULL 
        ");
    }

    /**
     * Reverse the migrations.
     * @return void
     * @throws Throwable
     */
    public function down()
    {
        DB::statement("SET FOREIGN_KEY_CHECKS=0;");
        DB::statement("TRUNCATE TABLE posts_tags;");
        DB::statement("TRUNCATE TABLE posts_photos;");
        DB::statement("TRUNCATE TABLE posts;");
        DB::statement("SET FOREIGN_KEY_CHECKS=1;");
    }
}
