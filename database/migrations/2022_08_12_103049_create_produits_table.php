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
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('designation')->nullable();
            $table->string('description')->nullable();
            $table->string('image')->nullable();
            $table->integer('pa')->default('0')->nullable();
            $table->integer('pv')->default('0')->nullable();
            $table->integer('limite')->nullable();
            $table->integer('qte')->default('0');
            $table->unsignedBigInteger('famille_id');
            $table->foreign('famille_id')->references('id')->on('familles');
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
        Schema::dropIfExists('produits');
    }
}
