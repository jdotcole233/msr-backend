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
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')
            ->on('users')->cascadeOnDelete();
            $table->string('fkWarehouseIDNo')->nullable();
            // $table->foreign('fkWarehouseIDNo')->references('warehouseIDNo')
            // ->on('tbl_warehouses')->cascadeOnDelete();
            $table->string('commodityName')->nullable();
            $table->string('packingSize', 255)->nullable()->change();
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
