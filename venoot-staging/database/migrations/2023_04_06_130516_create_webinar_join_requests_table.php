<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebinarJoinRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webinar_join_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->text('message')->nullable();
            $table->bigInteger('chatter_id')->unsigned();
            $table->timestamps();

            $table->foreign('chatter_id')->references('id')->on('chatters')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('webinar_join_requests');
    }
}
