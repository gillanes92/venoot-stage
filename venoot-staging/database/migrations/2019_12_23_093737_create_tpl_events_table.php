<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTplEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tpl_events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('id_template', 100);
            $table->bigInteger('tpl_id_event');
            $table->integer('tpl_type');
            $table->string('tpl_name', 250);
            $table->longText('tpl');
            $table->longText('css');
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
        Schema::dropIfExists('tpl_events');
    }
}
