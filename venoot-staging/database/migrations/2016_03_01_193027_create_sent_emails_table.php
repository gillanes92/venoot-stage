<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use jdavidbakr\MailTracker\Model\SentEmail;

class CreateSentEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection((new SentEmail)->getConnectionName())->create('sent_emails', function (Blueprint $table) {
            $table->increments('id');
            $table->char('hash', 32)->unique();
            $table->text('headers')->nullable();
            $table->string('sender')->nullable();
            $table->string('recipient')->nullable();
            $table->string('subject')->nullable();
            $table->text('content')->nullable();
            $table->integer('opens')->nullable();
            $table->integer('clicks')->nullable();
            $table->string('category')->nullable();
            $table->unsignedBigInteger('event_id')->nullable()->after('id');
            $table->unsignedBigInteger('participation_id')->nullable()->after('event_id');
            $table->unsignedBigInteger('user_id')->nullable()->after('participation_id');
            $table->timestamps();

            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('participation_id')->references('id')->on('participations')->onDelete('set null');
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
        Schema::connection((new SentEmail())->getConnectionName())->drop('sent_emails');
    }
}
