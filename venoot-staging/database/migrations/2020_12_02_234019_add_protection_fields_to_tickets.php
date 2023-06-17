<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProtectionFieldsToTickets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('protection')->nullable();
            $table->string('email')->nullable();
            $table->bigInteger('database_id')->nullable();
            $table->string('key')->nullable();
            $table->integer('protection_quantity')->unsigned()->nullable()->default(0);

            $table->foreign('database_id')->references('id')->on('databases')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign('tickets_database_id_foreign');

            $table->dropColumn('protection');
            $table->dropColumn('email');
            $table->dropColumn('database_id');
            $table->dropColumn('key');
            $table->dropColumn('protection_quantity');
        });
    }
}
