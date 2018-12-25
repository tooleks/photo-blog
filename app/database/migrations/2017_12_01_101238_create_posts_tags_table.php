<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTagsTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     * @throws Throwable
     */
    public function up()
    {
        Schema::create('posts_tags', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('post_id');
            $table->foreign('post_id', 'fk__posts_tags_post_id__posts_id')->references('id')->on('posts');
            $table->unsignedInteger('tag_id');
            $table->foreign('tag_id', 'fk__posts_tags_tag_id__tags_id')->references('id')->on('tags');
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     * @throws Throwable
     */
    public function down()
    {
        Schema::dropIfExists('posts_tags');
    }
}
