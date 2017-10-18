<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class RenamePhotoThumbnailsTableToPhotosThumbnailsTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     * @throws Throwable
     */
    public function up()
    {
        Schema::rename('photo_thumbnails', 'photos_thumbnails');
    }

    /**
     * Reverse the migrations.
     * @return void
     * @throws Throwable
     */
    public function down()
    {
        Schema::rename('photos_thumbnails', 'photo_thumbnails');
    }
}
