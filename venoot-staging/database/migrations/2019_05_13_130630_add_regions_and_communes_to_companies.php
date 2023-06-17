<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRegionsAndCommunesToCompanies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->integer('commune_id')->unsigned()->after('city');
            $table->integer('region_id')->unsigned()->after('commune_id');

            $table->foreign('commune_id')->references('id')->on('communes');
            $table->foreign('region_id')->references('id')->on('regions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropForeign('companies_commune_id_foreign');
            $table->dropForeign('companies_region_id_foreign');
            $table->dropColumn('commune_id');
            $table->dropColumn('region_id');
        });
    }
}
