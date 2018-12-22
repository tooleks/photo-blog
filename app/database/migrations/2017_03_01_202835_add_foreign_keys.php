<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('role_id', 'fk__users_role_id__roles_id')->references('id')->on('roles');
        });

        Schema::table('photos', function (Blueprint $table) {
            $table->foreign('user_id', 'fk__photos_user_id__users_id')->references('id')->on('users');
        });

        Schema::table('exif', function (Blueprint $table) {
            $table->foreign('photo_id', 'fk__exif_photo_id__photos_id')->references('id')->on('photos');
        });

        Schema::table('photo_tags', function (Blueprint $table) {
            $table->foreign('photo_id', 'fk__photo_tags_photo_id__photos_id')->references('id')->on('photos');
            $table->foreign('tag_id', 'fk__photo_tags_tag_id__tags_id')->references('id')->on('tags');
        });

        Schema::table('photo_thumbnails', function (Blueprint $table) {
            $table->foreign('photo_id', 'fk__photo_thumbnails_photo_id__photos_id')->references('id')->on('photos');
            $table->foreign('thumbnail_id', 'fk__photo_thumbnails_thumbnail_id__thumbnails_id')->references('id')->on('thumbnails');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('fk__users_role_id__roles_id');
            $table->dropIndex('fk__users_role_id__roles_id');
        });

        Schema::table('photos', function (Blueprint $table) {
            $table->dropForeign('fk__photos_user_id__users_id');
            $table->dropIndex('fk__photos_user_id__users_id');
        });

        Schema::table('exif', function (Blueprint $table) {
            $table->dropForeign('fk__exif_photo_id__photos_id');
            $table->dropIndex('fk__exif_photo_id__photos_id');
        });

        Schema::table('photo_tags', function (Blueprint $table) {
            $table->dropForeign('fk__photo_tags_photo_id__photos_id');
            $table->dropIndex('fk__photo_tags_photo_id__photos_id');
            $table->dropForeign('fk__photo_tags_tag_id__tags_id');
            $table->dropIndex('fk__photo_tags_tag_id__tags_id');
        });

        Schema::table('photo_thumbnails', function (Blueprint $table) {
            $table->dropForeign('fk__photo_thumbnails_photo_id__photos_id');
            $table->dropIndex('fk__photo_thumbnails_photo_id__photos_id');
            $table->dropForeign('fk__photo_thumbnails_thumbnail_id__thumbnails_id');
            $table->dropIndex('fk__photo_thumbnails_thumbnail_id__thumbnails_id');
        });
    }
}
