<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRefToSortieStocks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sortie_stocks', function (Blueprint $table) {
            //
            $table->string("ref")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sortie_stocks', function (Blueprint $table) {
            //
            $table->dropColumn('ref');
        });
    }
}
