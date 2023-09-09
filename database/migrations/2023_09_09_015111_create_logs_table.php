<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('logs')) {
            Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->string("designation")->nullable();
            $table->integer('id_evnt');
            $table->string("date");
            $table->integer("prix")->default(0);
            $table->integer("remise")->default(0);  
            $table->integer("avance")->default(0);
            $table->integer("montant")->default(0);
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
        Schema::dropIfExists('logs');
    }
}
