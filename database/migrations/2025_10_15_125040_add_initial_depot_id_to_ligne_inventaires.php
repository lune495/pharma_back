<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInitialDepotIdToLigneInventaires extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ligne_inventaires', function (Blueprint $table) {
            $table->foreignId('initial_depot_id')->nullable()->constrained()->references('id')->on('initial_depots');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ligne_inventaires', function (Blueprint $table) {
            $table->dropForeign(['initial_depot_id']);
        });
    }
}
