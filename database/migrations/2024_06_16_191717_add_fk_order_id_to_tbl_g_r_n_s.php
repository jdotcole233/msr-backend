<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFkOrderIdToTblGRNS extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_g_r_n_s', function (Blueprint $table) {
            $table->id("fkOrderId")->nullable();
            // $table->foreign("fkOrderId")->references("id")->on("tbl_orders")->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_g_r_n_s', function (Blueprint $table) {
            $table->dropForeign("fkOrderId");
            $table->dropColumn("fkOrderId");
        });
    }
}
