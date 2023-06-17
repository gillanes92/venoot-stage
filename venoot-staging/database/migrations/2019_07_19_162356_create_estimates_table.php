<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstimatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estimates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('landing');
            $table->boolean('confirmation_form');
            $table->boolean('mailings');
            $table->integer('mailings_quantity');
            $table->boolean('polls');
            $table->integer('polls_quantity');
            $table->boolean('register_keys');
            $table->integer('register_keys_quantity');
            $table->boolean('ticket_sale');
            $table->boolean('free_tickets');
            $table->integer('net_total')->default(0);
            $table->integer('status')->default(0);
            $table->integer('company_id')->unsigned();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('estimates');
    }
}
