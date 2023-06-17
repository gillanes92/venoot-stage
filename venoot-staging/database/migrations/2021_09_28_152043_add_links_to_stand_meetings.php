<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLinksToStandMeetings extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('stand_meetings', function (Blueprint $table) {
      $table->string('manager_link')->nullable();
      $table->string('participant_link')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('stand_meetings', function (Blueprint $table) {
      $table->dropColumn('manager_link');
      $table->dropColumn('participant_link');
    });
  }
}
