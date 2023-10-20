<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPuToLigneApprovisionnement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ligne_approvisionnements', function (Blueprint $table) {
            //
            $table->integer('pu')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ligne_approvisionnements', function (Blueprint $table) {
            //
            $table->dropColumn('pu');
        });
    }
}
