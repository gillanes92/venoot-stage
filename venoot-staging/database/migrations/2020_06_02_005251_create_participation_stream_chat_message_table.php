<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParticipationStreamChatMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participation_stream_chat_message', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('participation_id');
            $table->bigInteger('stream_chat_message_id');
            $table->timestamps();

            $table->foreign('participation_id')->references('id')->on('participations')->onDelete('cascade');
            $table->foreign('stream_chat_message_id')->references('id')->on('stream_chat_messages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('participation_stream_chat_message');
    }
}
