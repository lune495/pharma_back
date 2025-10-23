<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProduitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('produits')) {
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('designation')->nullable();
            $table->string('description')->nullable();
            $table->string('image')->nullable();
            $table->integer('pa')->nullable();
            $table->integer('pv')->nullable();
            $table->integer('limite')->nullable();
            $table->integer('qte')->nullable();
            $table->unsignedBigInteger('famille_id');
            $table->foreign('famille_id')->references('id')->on('familles');
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
        Schema::dropIfExists('produits');
    }
}
