<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDefaultTemplatesToEvents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->bigInteger('confirmation_id')->unsigned()->nullable();
            $table->bigInteger('reconfirmation_id')->unsigned()->nullable();
            $table->bigInteger('qrcode_id')->unsigned()->nullable();
            $table->bigInteger('reqrcode_id')->unsigned()->nullable();

            $table->foreign('confirmation_id')->references('id')->on('templates')->onDelete('cascade');
            $table->foreign('reconfirmation_id')->references('id')->on('templates')->onDelete('cascade');
            $table->foreign('qrcode_id')->references('id')->on('templates')->onDelete('cascade');
            $table->foreign('reqrcode_id')->references('id')->on('templates')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign('events_confirmation_id_foreign');
            $table->dropForeign('events_reconfirmation_id_foreign');
            $table->dropForeign('events_qrcode_id_foreign');
            $table->dropForeign('events_reqrcode_id_foreign');

            $table->dropColumn('confirmation_id');
            $table->dropColumn('reconfirmation_id');
            $table->dropColumn('qrcode_id');
            $table->dropColumn('reqrcode_id');
        });
    }
}
