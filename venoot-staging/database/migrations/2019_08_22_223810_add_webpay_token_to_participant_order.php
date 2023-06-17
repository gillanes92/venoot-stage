<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWebpayTokenToParticipantOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('participant_orders', function (Blueprint $table) {
            $table->string('webpay_token')->nullable();
            $table->string('return_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('participant_orders', function (Blueprint $table) {
            $table->dropColumn('webpay_token');
            $table->dropColumn('return_url');
        });
    }
}
