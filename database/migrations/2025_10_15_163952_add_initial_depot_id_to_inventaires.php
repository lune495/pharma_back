<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInitialDepotIdToInventaires extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventaires', function (Blueprint $table) {
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
        Schema::table('inventaires', function (Blueprint $table) {
            $table->dropForeign(['initial_depot_id']);
            $table->dropColumn('initial_depot_id');
        });
    }
}
