<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToLandings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('landings', function (Blueprint $table) {
            $table->boolean('has_date')->nullable()->default(true);
            $table->boolean('has_add_to_calendar')->nullable()->default(true);
            $table->boolean('show_sponsor_title')->nullable()->default(true);
            $table->string('confirm_subtitle')->nullable()->default('confirm_subtitle');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('landings', function (Blueprint $table) {
            $table->dropColumn('has_date');
            $table->dropColumn('has_add_to_calendar');
            $table->dropColumn('show_sponsor_title');
            $table->dropColumn('confirm_subtitle');
        });
    }
}
