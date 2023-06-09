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
        Schema::table('collabo_infos', function (Blueprint $table) {
            $table->id();
            $table->string('title',255);
            $table->string('video_id',255);
            $table->string('channel',255);
            $table->string('channel_id',255);
            $table->string('status');
            $table->timestamp('published_at')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->integer('start')->nullable();
            $table->string('description',255);
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
        Schema::table('collabo_infos', function (Blueprint $table) {
            //
        });
    }
};
