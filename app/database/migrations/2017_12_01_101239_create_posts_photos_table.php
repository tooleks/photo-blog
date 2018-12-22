<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsPhotosTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     * @throws Throwable
     */
    public function up()
    {
        Schema::create('posts_photos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('post_id');
            $table->foreign('post_id', 'fk__posts_photos_post_id__posts_id')->references('id')->on('posts');
            $table->unsignedInteger('photo_id');
            $table->foreign('photo_id', 'fk__posts_photos_photo_id__photos_id')->references('id')->on('photos');
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     * @throws Throwable
     */
    public function down()
    {
        Schema::dropIfExists('posts_photos');
    }
}
