<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProductsFieldsToEstimates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estimates', function (Blueprint $table) {
            $table->boolean('administration')->default(false);
            $table->boolean('graphical_design')->default(false);
            $table->boolean('registering')->default(false);
            $table->boolean('lanyards')->default(false);
            $table->boolean('credentials')->default(false);
            $table->boolean('collectors_half')->default(false);
            $table->boolean('collectors_full')->default(false);
            $table->boolean('development')->default(false);
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
            $table->dropColumn('administration');
            $table->dropColumn('graphical_design');
            $table->dropColumn('registering');
            $table->dropColumn('lanyards');
            $table->dropColumn('credentials');
            $table->dropColumn('collectors_half');
            $table->dropColumn('collectors_full');
            $table->dropColumn('development');
        });
    }
}
