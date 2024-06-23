<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGhanaCardToTblWarehousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_warehouses', function (Blueprint $table) {
            $table->string('ghanaCard')->nullable()->unique()->after('declarationAccepted');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_warehouses', function (Blueprint $table) {
            $table->dropColumn('ghanaCard');
        });
    }
}
