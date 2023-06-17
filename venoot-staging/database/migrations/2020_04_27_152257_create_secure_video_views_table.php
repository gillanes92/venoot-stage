<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSecureVideoViewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('secure_video_views', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('participation_id')->unsigned();
            $table->bigInteger('event_id')->unsigned();
            $table->string('video_url', 4096);
            $table->dateTime('seen_at');
            $table->dateTime('stopped_at')->nullable();
            $table->timestamps();

            $table->foreign('participation_id')->references('id')->on('participations')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('secure_video_views');
    }
}
