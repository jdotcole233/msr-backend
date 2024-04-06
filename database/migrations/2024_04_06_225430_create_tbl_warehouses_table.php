<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblWarehousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_warehouses', function (Blueprint $table) {
            $table->id();
            $table->foreign('user_id')->references('id')
            ->on('users')->cascadeOnDelete();
            $table->string('warehouseIDNo', 255)->nullable();
            $table->string('registeredName', 255)->nullable();
            $table->string('region', 255)->nullable();
            $table->string('townCity', 255)->nullable();
            $table->string('district', 255)->nullable();
            $table->string('ghPostAddress')->nullable();
            $table->string('GPSLat')->nullable();
            $table->string('GPSLong')->nullable();
            $table->string('yearEstablished')->nullable();
            $table->string('businessType')->nullable();
            $table->double('storageCapacity')->nullable();
            $table->boolean('offersCleaning')->nullable();
            $table->boolean('offersRebagging')->nullable();
            $table->string('mainContactName')->nullable();
            $table->boolean('mainContactIsMale')->nullable();
            $table->string('mainContactPosition')->nullable();
            $table->string('mainContactTel')->nullable();
            $table->string('mainContactEmail')->nullable();
            $table->boolean('declarationAccepted')->nullable();
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
        Schema::dropIfExists('tbl_warehouses');
    }
}
