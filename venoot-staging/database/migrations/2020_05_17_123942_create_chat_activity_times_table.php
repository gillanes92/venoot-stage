<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatActivityTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_activity_times', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('participation_id');
            $table->bigInteger('activity_id')->nullable();
            $table->timestamps();

            $table->foreign('participation_id')->references('id')->on('participations')->onDelete('cascade');
            $table->foreign('activity_id')->references('id')->on('activities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat_activity_times');
    }
}
