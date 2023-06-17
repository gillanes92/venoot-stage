<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCssEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('css_events', function (Blueprint $table) {
            $table->bigIncrements('id_css');
            $table->string('id_template', 100);
            $table->bigInteger('id_evento')->unsigned();
            $table->bigInteger('id_elemento')->unsigned();
            $table->text('propiedades');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('css_events');
    }
}
