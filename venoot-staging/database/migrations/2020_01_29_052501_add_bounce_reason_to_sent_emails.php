<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBounceReasonToSentEmails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sent_emails', function (Blueprint $table) {
            $table->string('bounce_type', 100)->nullable();
            $table->string('bounce_reason', 100)->nullable();
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
            $table->dropColumn('bounce_type');
            $table->dropColumn('bounce_reason');
        });
    }
}
