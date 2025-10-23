<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLigneInventairesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ligne_inventaires', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventaire_id');
            $table->foreign('inventaire_id')->references('id')->on('inventaires')->onDelete('cascade');
            $table->unsignedBigInteger('produit_id');
            $table->foreign('produit_id')->references('id')->on('produits')->onDelete('cascade');
            $table->integer('quantite_reel')->default(0);
            $table->integer('quantite_theorique')->default(0);
            $table->integer('diff_inventaire')->default(0);
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
        Schema::dropIfExists('ligne_inventaires');
    }
}
