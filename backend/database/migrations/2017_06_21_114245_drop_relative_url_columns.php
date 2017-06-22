<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropRelativeUrlColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('photos', function (Blueprint $table) {
            $table->dropColumn('relative_url');
        });

        Schema::table('thumbnails', function (Blueprint $table) {
            $table->dropColumn('relative_url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('photos', function (Blueprint $table) {
            $table->string('relative_url')->after('path')->default('');
        });

        Schema::table('thumbnails', function (Blueprint $table) {
            $table->string('relative_url')->after('path')->default('');
        });
    }
}
