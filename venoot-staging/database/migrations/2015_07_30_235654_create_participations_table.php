<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParticipationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('participant_id')->unsigned();
            $table->integer('event_id')->unsigned();
            $table->uuid('uuid');
            $table->dateTime('invitation_sent_at')->nullable();
            $table->boolean('confirmed_status')->nullable();
            $table->dateTime('confirmed_sent_at')->nullable();
            $table->dateTime('confirmed_at')->nullable();
            $table->dateTime('registered_at')->nullable();
            $table->timestamps();

            $table->foreign('participant_id')->references('id')->on('participants')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('participations');
    }
}
