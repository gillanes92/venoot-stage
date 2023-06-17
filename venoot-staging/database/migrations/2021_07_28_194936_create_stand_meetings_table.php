<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStandMeetingsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('stand_meetings', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->bigInteger('stand_id')->unsigned();
      $table->bigInteger('participation_id')->unsigned()->nullable();
      $table->datetime('start');
      $table->datetime('end');
      $table->string('status')->default('closed');
      $table->timestamps();

      //$table->foreign('stand_id')->references('id')->on('stands')->onDelete('cascade');
      $table->foreign('participation_id')->references('id')->on('participations')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('stand_meetings');
  }
}
