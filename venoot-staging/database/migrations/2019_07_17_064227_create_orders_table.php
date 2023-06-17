<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('event_publish')->default(0);
            $table->integer('event_landing')->default(0);
            $table->integer('poll_publish')->default(0);
            $table->integer('mailings')->default(0);
            $table->integer('register_keys')->default(0);
            $table->integer('register_days')->default(0);
            $table->integer('status')->default(0);
            $table->integer('company_id')->unsigned();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
