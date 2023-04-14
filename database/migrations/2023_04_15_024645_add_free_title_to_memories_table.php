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
        Schema::table('memories', function (Blueprint $table) {
            DB::statement("ALTER TABLE memories ADD free_title TEXT");
            DB::statement("ALTER TABLE memories ADD FULLTEXT index ftx_free_title (free_title) with parser ngram");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('memories', function (Blueprint $table) {
            $table->dropColumn('free_title');
        });
    }
};
