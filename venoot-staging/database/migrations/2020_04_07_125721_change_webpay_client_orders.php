<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeWebpayClientOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('webpay_client_orders', function (Blueprint $table) {
            $table->string('token')->nullable()->change();
            $table->string('order')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('webpay_client_orders', function (Blueprint $table) {
            $table->string('token')->change();
            $table->string('order')->change();
        });
    }
}
