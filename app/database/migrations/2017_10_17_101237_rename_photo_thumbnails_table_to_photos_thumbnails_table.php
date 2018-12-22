<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

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
