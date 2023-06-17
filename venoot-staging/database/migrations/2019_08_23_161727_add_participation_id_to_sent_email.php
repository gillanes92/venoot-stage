<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddParticipationIdToSentEmail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sent_emails', function (Blueprint $table) {
            $table->string('category')->nullable();
            $table->unsignedBigInteger('participation_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('participation_id')->references('id')->on('participations')->onDelete('set null');
            $table->unsignedBigInteger('event_id')->nullable();
            $table->foreign('event_id')->references('id')->on('events')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sent_emails', function (Blueprint $table) {
            $table->dropForeign('sent_emails_participation_id_foreign');
            $table->dropColumn('participation_id');
            $table->dropColumn('category');
            $table->dropForeign('sent_emails_event_id_foreign');
            $table->dropColumn('event_id');
            $table->dropForeign('sent_emails_user_id_foreign');
            $table->dropColumn('user_id');
        });
    }
}
