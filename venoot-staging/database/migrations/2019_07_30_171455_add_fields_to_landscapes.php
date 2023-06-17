<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToLandscapes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('landings', function (Blueprint $table) {
            $table->string('highlight')->nullable();
            $table->string('second_title')->nullable();
            $table->boolean('has_description')->default(0);
            $table->string('description_title')->default('descripción');
            $table->string('where_title')->default('donde');
            $table->string('when_title')->default('cuando');
            $table->string('hour_title')->default('hora');
            $table->boolean('has_speakers')->default(0);
            $table->string('speakers_title')->default('speakers');
            $table->boolean('has_activities')->default(0);
            $table->string('activities_title')->default('agenda');
            $table->boolean('has_location')->default(0);
            $table->string('location_title')->default('donde');
            $table->boolean('has_sponsors')->default(0);
            $table->string('sponsors_title')->default('auspiciadores');
            $table->string('images_title')->default('galería');
            $table->string('confirm_title')->default('confirmación');
            $table->boolean('has_contact')->default(0);
            $table->string('contact_title')->default('contacto');
            $table->boolean('has_ssnn')->default(0);
            $table->string('ssnn_title')->default('redes sociales');
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
            $table->dropColumn('highlight');
            $table->dropColumn('second_title');
            $table->dropColumn('has_description');
            $table->dropColumn('description_title');
            $table->dropColumn('where_title');
            $table->dropColumn('when_title');
            $table->dropColumn('hour_title');
            $table->dropColumn('has_speakers');
            $table->dropColumn('speakers_title');
            $table->dropColumn('has_activities');
            $table->dropColumn('activities_title');
            $table->dropColumn('has_location');
            $table->dropColumn('location_title');
            $table->dropColumn('has_sponsors');
            $table->dropColumn('sponsors_title');
            $table->dropColumn('images_title');
            $table->dropColumn('confirm_title');
            $table->dropColumn('has_contact');
            $table->dropColumn('contact_title');
            $table->dropColumn('has_ssnn');
            $table->dropColumn('ssnn_title');
        });
    }
}
