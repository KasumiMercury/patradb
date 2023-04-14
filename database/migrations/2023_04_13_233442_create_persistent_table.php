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
        Schema::create('persistent', function (Blueprint $table) {
            $table->id();
            $table->dateTime('start_date')->comment('開始日');
            $table->string('event_title')->comment('イベント名');
            $table->string('event_en')->comment('イベント名_英語');
            $table->string('event_code')->comment('イベントコード')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('persistent');
    }
};
