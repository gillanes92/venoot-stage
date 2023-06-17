<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeenAtEmails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seen_at_emails', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('sent_email_id');
            $table->dateTime('seen_at')->useCurrent();
            $table->timestamps();

            $table->foreign('sent_email_id')->references('id')->on('sent_emails')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seen_at_emails');
    }
}
