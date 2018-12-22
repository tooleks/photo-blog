<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeIsPublishedColumnToPublishedAtOfPhotosTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     * @throws Throwable
     */
    public function up()
    {
        try {
            DB::beginTransaction();
            Schema::table('photos', function (Blueprint $table) {
                $table->timestamp('published_at')->nullable()->after('is_published');
            });
            DB::statement('UPDATE photos SET published_at = created_at WHERE is_published = TRUE;');
            Schema::table('photos', function (Blueprint $table) {
                $table->dropColumn('is_published');
            });
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Reverse the migrations.
     * @return void
     * @throws Throwable
     */
    public function down()
    {
        try {
            DB::beginTransaction();
            Schema::table('photos', function (Blueprint $table) {
                $table->boolean('is_published')->default(false)->after('published_at');
            });
            DB::statement('UPDATE photos SET is_published = TRUE WHERE published_at IS NOT NULL;');
            Schema::table('photos', function (Blueprint $table) {
                $table->dropColumn('published_at');
            });
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
