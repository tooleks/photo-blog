<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropPostsColumnsFromPhotosTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     * @throws Throwable
     */
    public function up()
    {
        Schema::table('photos', function (Blueprint $table) {
            $table->dropColumn('description');
            $table->dropColumn('published_at');
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     * @throws Throwable
     */
    public function down()
    {
        Schema::table('photos', function (Blueprint $table) {
            $table->text('description');
            $table->timestamp('published_at')->nullable()->after('avg_color');
        });
    }
}
