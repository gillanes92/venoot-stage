<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParticipationSendingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participation_sending', function (Blueprint $table) {
            $table->integer('participation_id')->unsigned()->index();
            $table->foreign('participation_id')->references('id')->on('participations')->onDelete('cascade');

            $table->integer('sending_id')->unsigned()->index();
            $table->foreign('sending_id')->references('id')->on('sendings')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('participation_sending');
    }
}
