<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // create free_title column and free_description column, index them
        Schema::table('videos', function (Blueprint $table) {
            DB::statement("ALTER TABLE videos ADD free_title TEXT");
            DB::statement("ALTER TABLE videos ADD FULLTEXT index ftx_free_title (free_title) with parser ngram");
            DB::statement("ALTER TABLE videos ADD free_description TEXT");
            DB::statement("ALTER TABLE videos ADD FULLTEXT index ftx_free_description (free_description) with parser ngram");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('videos', function (Blueprint $table) {
            // drop free_title column and free_description column
            $table->dropColumn('free_title');
            $table->dropColumn('free_description');
        });
    }
};
