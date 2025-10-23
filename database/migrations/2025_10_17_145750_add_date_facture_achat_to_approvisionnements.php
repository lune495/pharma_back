<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDateFactureAchatToApprovisionnements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('approvisionnements', function (Blueprint $table) {
            $table->string('date_facture_achat')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('approvisionnements', function (Blueprint $table) {
            $table->dropColumn('date_facture_achat');
        });
    }
}
