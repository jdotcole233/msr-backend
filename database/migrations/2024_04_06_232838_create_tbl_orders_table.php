<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_orders', function (Blueprint $table) {
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
            $table->string('contactPhone')->nullable();
            $table->boolean('isBuyOrder')->nullable();
            $table->string('transactionType')->nullable();
            $table->text('orderDetails')->nullable();
            $table->boolean('isComplete')->nullable();
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
        Schema::dropIfExists('tbl_orders');
    }
}
