<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParticipantQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participant_questions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('participation_id');
            $table->bigInteger('activity_id')->nullable();
            $table->bigInteger('question_request_id');
            $table->string('question');
            $table->integer('votes')->unsigned()->default(0);
            $table->timestamps();

            $table->foreign('participation_id')->references('id')->on('participations')->onDelete('cascade');
            $table->foreign('activity_id')->references('id')->on('activities')->onDelete('cascade');
            $table->foreign('question_request_id')->references('id')->on('question_requests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('participant_questions');
    }
}
