<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('ventes')) {
        Schema::create('ventes', function (Blueprint $table) {
            $table->id();
            $table->integer('montant')->nullable();
            $table->integer('qte')->default('0');
            $table->integer('montantencaisse')->nullable()->default('0');
            $table->integer('monnaie')->nullable()->default('0');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ventes');
    }
}
