<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblOperatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_operators', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')
            ->on('users')->cascadeOnDelete();
            $table->string('fkWarehouseIDNo')->nullable();
            // $table->foreign('fkWarehouseIDNo')->references('warehouseID')
            // ->on('tbl_warehouses')->cascadeOnDelete();
            $table->string('operatorName')->nullable();
            $table->string('contactPhone')->nullable();
            $table->enum('ageGroup', ['15-29', '30+', 'mixed'])->nullable();
            $table->boolean('isMale')->nullable();
            $table->boolean('isOwner')->nullable();
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
        Schema::dropIfExists('tbl_operators');
    }
}
