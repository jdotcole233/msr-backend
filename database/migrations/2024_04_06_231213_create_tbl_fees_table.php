<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_fees', function (Blueprint $table) {
            $table->id();
            $table->foreign('user_id')->references('id')
            ->on('users')->cascadeOnDelete();
            $table->foreign('fkWarehouseIDNo')->references('warehouseID')
            ->on('tbl_warehouses')->cascadeOnDelete();
            $table->string('commodityType')->nullable();
            $table->double('storageFee')->nullable();
            $table->double('loadingFee')->nullable();
            $table->double('unloadingFee')->nullable();
            $table->double('cleaningFee')->nullable();
            $table->double('rebaggingFee')->nullable();
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
        Schema::dropIfExists('tbl_fees');
    }
}
