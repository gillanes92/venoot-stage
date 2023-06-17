<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddParticipantIdToParticipantTickets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('participant_tickets', function (Blueprint $table) {
            $table->bigInteger('participant_id')->nullable();
            
            $table->foreign('participant_id')->references('id')->on('participants')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('participant_tickets', function (Blueprint $table) {
            $table->dropForeign('participant_tickets_ticket_id_foreign');
            $table->dropColumn('participant_id');
        });
    }
}
