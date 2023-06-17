<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOtherFieldsToEstimates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estimates', function (Blueprint $table) {
            $table->integer('confirmed_amount')->unsigned()->default(0);
            $table->integer('lanyard_amount')->unsigned()->default(0);
            $table->integer('credential_amount')->unsigned()->default(0);
            $table->integer('collector_half_amount')->unsigned()->default(0);
            $table->integer('collector_full_amount')->unsigned()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('estimates', function (Blueprint $table) {
            $table->dropColumn('confirmed_amount');
            $table->dropColumn('lanyard_amount');
            $table->dropColumn('credential_amount');
            $table->dropColumn('collector_half_amount');
            $table->dropColumn('collector_full_amount');
        });
    }
}
