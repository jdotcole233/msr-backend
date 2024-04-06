<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblCommoditiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_commodities', function (Blueprint $table) {
            $table->id();
            $table->foreign('user_id')->references('id')
            ->on('users')->cascadeOnDelete();
            $table->foreign('fkWarehouseIDNo')->references('warehouseID')
            ->on('tbl_warehouses')->cascadeOnDelete();
            $table->string('commodityName')->nullable();
            $table->double('packingSize')->nullable();
            $table->integer('status')->nullable();
            $table->string('lastUpdatedByName')->nullable();
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
        Schema::dropIfExists('tbl_commodities');
    }
}
