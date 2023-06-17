<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsWebinarToEvents extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('events', function (Blueprint $table) {
      $table->boolean('is_webinar')->default(false);

      $table->string('banner')->nullable()->change();
      $table->string('logo_event')->nullable()->change();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('events', function (Blueprint $table) {
      $table->dropColumn('is_webinar');
      $table->string('banner')->change();
      $table->string('logo_event')->change();
    });
  }
}
