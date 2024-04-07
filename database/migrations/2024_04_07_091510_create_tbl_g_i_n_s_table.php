<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblGINSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_g_i_n_s', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')
            ->on('users')->cascadeOnDelete();
            $table->string('fkWarehouseIDNo')->nullable();
            // $table->foreign('fkWarehouseIDNo')->references('warehouseID')
            // ->on('tbl_warehouses')->cascadeOnDelete();
            $table->unsignedBigInteger('fkActorID')->nullable();
            $table->foreign('fkActorID')->references('id')
            ->on('tbl_actors')->cascadeOnDelete();
            $table->unsignedBigInteger('fktblWHCommoditiesID')->nullable();
            $table->foreign('fktblWHCommoditiesID')->references('id')
            ->on('tbl_commodities')->cascadeOnDelete();
            $table->dateTime('dateIssued')->nullable();
            $table->integer('noBagsIssued')->nullable();
            $table->double('weightPerBag')->nullable();
            $table->double('pricePerBag')->nullable();
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
        Schema::dropIfExists('tbl_g_i_n_s');
    }
}
