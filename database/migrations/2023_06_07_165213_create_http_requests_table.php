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
        Schema::create('http_requests', function (Blueprint $table) {
            $table->id();
            $table->string('name',255);
            $table->string('method',10);
            $table->string('path',255);
            $table->json('query');
            $table->integer('user_id')->nullable();
            $table->string('user_agent',255);
            $table->string('ip',255)->nullable();
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
        Schema::dropIfExists('http_requests');
    }
};
