<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWoocommerceFieldsToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('package')->default('free');
            $table->date('expiration_date')->nullable();
            $table->string('category')->default('normal');
	    $table->bigInteger('customer_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('package');
            $table->dropColumn('expiration_date');
            $table->dropColumn('category');
	    $table->dropColumn('customer_id');
        });
    }
}
