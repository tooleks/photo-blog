<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropPhotosTagsTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     * @throws Throwable
     */
    public function up()
    {
        Schema::dropIfExists('photos_tags');
    }

    /**
     * Reverse the migrations.
     * @return void
     * @throws Throwable
     */
    public function down()
    {
        Schema::create('photo_tags', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('photo_id');
            $table->unsignedInteger('tag_id');
        });
    }
}
