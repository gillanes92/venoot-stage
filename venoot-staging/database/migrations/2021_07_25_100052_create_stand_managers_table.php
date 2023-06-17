<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStandManagersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('stand_managers', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->bigInteger('stand_id')->unsigned()->nullable();
      $table->string('role')->nullable()->default('administrator');
      $table->string('name');
      $table->string('last_name');
      $table->string('email')->unique();
      $table->string('job')->nullable()->default(null);
      $table->string('company')->nullable()->default(null);
      $table->string('password');
      $table->timestamps();

      //$table->foreign('stand_id')->references('id')->on('stands')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('stand_managers');
  }
}
