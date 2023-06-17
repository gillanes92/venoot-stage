<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContactFieldsToLandings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('landings', function (Blueprint $table) {
            $table->string('contact_subtitle', 180)->nullable();
            $table->string('address_title', 120)->default('dirección');
            $table->string('address', 180)->nullable();
            $table->string('phone_title', 120)->default('teléfono');
            $table->string('phone', 180)->nullable();
            $table->boolean('show_email')->default(true);
            $table->boolean('show_contact_form')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('landings', function (Blueprint $table) {
            $table->dropColumn('contact_subtitle');
            $table->dropColumn('address_title');
            $table->dropColumn('phone_title');
            $table->dropColumn('show_email');
            $table->dropColumn('show_contact_form');
        });
    }
}
