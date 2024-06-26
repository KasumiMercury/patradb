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
        Schema::table('schedule_infos', function (Blueprint $table) {
            $table->boolean('checked')->default(false)->after('creater_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('schedule_infos', function (Blueprint $table) {
            $table->dropColumn('checked');
        });
    }
};
