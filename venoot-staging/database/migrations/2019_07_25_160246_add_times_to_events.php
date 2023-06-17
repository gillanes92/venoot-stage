<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimesToEvents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->date('start_date')->change();
            $table->time('start_time')->nullable()->after('start_date');
            $table->date('end_date')->change();
            $table->time('end_time')->nullable()->after('end_date');
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
            $table->dateTime('start_date')->change();
            $table->dropColumn('start_time');
            $table->dateTime('end_date')->change();
            $table->dropColumn('end_time');
        });
    }
}
