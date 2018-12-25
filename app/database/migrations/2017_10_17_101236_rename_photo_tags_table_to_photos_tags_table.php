<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class RenamePhotoTagsTableToPhotosTagsTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     * @throws Throwable
     */
    public function up()
    {
        Schema::rename('photo_tags', 'photos_tags');
    }

    /**
     * Reverse the migrations.
     * @return void
     * @throws Throwable
     */
    public function down()
    {
        Schema::rename('photos_tags', 'photo_tags');
    }
}
