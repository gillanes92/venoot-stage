<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWebpayClientOrdersToEstimates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estimates', function (Blueprint $table) {
            $table->unsignedBigInteger('webpay_client_order_id')->nullable();
            $table->foreign('webpay_client_order_id')->references('id')->on('webpay_client_orders');
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
            $table->dropColumn('webpay_client_order_id');
            $table->dropForeign('estimates_webpay_client_order_id_foreign');
        });
    }
}
