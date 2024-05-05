<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGinidnoToTblGINSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_g_i_n_s', function (Blueprint $table) {
            $table->string('ginidno', 121)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_g_i_n_s', function (Blueprint $table) {
            $table->dropColumn('ginidno');
        });
    }
}
