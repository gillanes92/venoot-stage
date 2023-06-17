<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParticipationQuestionRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participation_question_request', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('participation_id');
            $table->bigInteger('question_request_id');
            $table->timestamps();

            $table->foreign('participation_id')->references('id')->on('participations')->onDelete('cascade');
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
        Schema::dropIfExists('participation_question_request');
    }
}
