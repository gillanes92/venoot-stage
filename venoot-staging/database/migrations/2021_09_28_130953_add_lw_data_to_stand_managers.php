<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLwDataToStandManagers extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('stand_managers', function (Blueprint $table) {
      $table->bigInteger('lw_id')->nullable();
      $table->string('lw_password')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('stand_managers', function (Blueprint $table) {
      $table->dropColumn('lw_id');
      $table->dropColumn('lw_password');
    });
  }
}
